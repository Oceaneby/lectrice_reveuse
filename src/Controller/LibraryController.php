<?php

namespace App\Controller;

use App\Entity\Library;
use App\Entity\User;
use App\Entity\Book;
use App\Entity\Bookshelf;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\LibraryRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/library')]
final class LibraryController extends AbstractController
{
    #[Route(name: 'app_library')]
    public function viewLibrary(LibraryRepository $libraryRepository): Response
    {
        $user = $this->getUser();


        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $library = $libraryRepository->findOneBy(['user' => $user]);
        if (!$library) {
            return $this->render('library/empty.html.twig');
        }

        $bookshelves = $library->getBookshelves();
        $book = $library->getBooks();


        $book = $library->getBooks()->toArray();
        return $this->render('library/index.html.twig', [
            'library' => $library,
            'bookshelves' => $bookshelves,
            'books' => $book,
        ]);
    }

    #[Route('/library/add/{bookId}', name: 'app_library_add', methods: ['POST'])]
    public function addBookToLibrary(int $bookId, BookRepository $bookRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $book = $bookRepository->find($bookId);
        if (!$book) {
            throw $this->createNotFoundException('Le livre demandé n\'existe pas.');
        }

        // Récupérer la bibliothèque de l'utilisateur
        $library = $em->getRepository(Library::class)->findOneBy(['user' => $user]);
        if (!$library) {
            // Si l'utilisateur n'a pas de bibliothèque, créer une nouvelle entrée
            $library = new Library();
            $library->setUser($user);
        }

        // Vérifier si le livre est déjà dans la bibliothèque de l'utilisateur
        if ($library->getBooks()->contains($book)) {
            $this->addFlash('info', 'Le livre est déjà dans votre bibliothèque.');
            return $this->redirectToRoute('app_library');
        }

        $libraryEntry = $library;
        $libraryEntry->setUser($user)
            ->addBook($book)
            ->setAddedDate(new \DateTime());


        $em->persist($libraryEntry);
        $em->flush();

        $this->addFlash('success', 'Le livre a été ajouté à votre bibliothèque.');
        return $this->redirectToRoute('app_library');
    }
    #[Route('/library/remove/{bookId}', name: 'app_library_remove', methods: ['POST'])]
    public function removeBookFromLibrary(int $bookId, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer la bibliothèque de l'utilisateur
        $library = $em->getRepository(Library::class)->findOneBy(['user' => $user]);
        if (!$library) {
            throw $this->createNotFoundException('Votre bibliothèque n\'existe pas.');
        }

        // Récupérer le livre
        $book = $em->getRepository(Book::class)->find($bookId);
        if (!$book) {
            throw $this->createNotFoundException('Le livre demandé n\'existe pas.');
        }

        // Vérifier si le livre fait partie de la bibliothèque
        if (!$library->getBooks()->contains($book)) {
            throw $this->createNotFoundException('Le livre ne fait pas partie de votre bibliothèque.');
        }

        // Retirer l'association du livre avec la bibliothèque

        $library->removeBook($book);

        $em->persist($library);
        $em->flush();

        $this->addFlash('success', 'Le livre a été retiré de votre bibliothèque.');
        return $this->redirectToRoute('app_library');
    }

    // Afficher la liste de tous les livres disponibles à ajouter
    #[Route('/add', name: 'app_book_list')]
    public function listBooks(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();

        return $this->render('library/add.html.twig', [
            'books' => $books,
        ]);
    }


    #[Route('/library/create-shelf', name: 'app_library_create_shelf', methods: ['GET', 'POST'])]
    public function createShelf(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $library = $em->getRepository(Library::class)->findOneBy(['user' => $user]);
        if (!$library) {
            // Si l'utilisateur n'a pas de bibliothèque, en créer une
            $library = new Library();
            $library->setUser($user);
            $em->persist($library);
            $em->flush();
        }

        $shelf = new Bookshelf();
        $form = $this->createFormBuilder($shelf)
            ->add('shelfName', TextType::class, [
                'label' => 'Nom de l\'étagère'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shelf->setLibrary($library);
            $shelf->setUser($user);
            $em->persist($shelf);
            $em->flush();

            $this->addFlash('success', 'L\'étagère a été créée avec succès.');
            return $this->redirectToRoute('app_library');
        }

        return $this->render('library/create_shelf.html.twig', [
            'form' => $form->createView(),
            'shelf' => $shelf
        ]);
    }



    #[Route('/library/add-to-shelf/{bookId}', name: 'app_library_add_to_shelf', methods: ['POST'])]
    public function addBookToShelf(int $bookId, Request $request, BookRepository $bookRepository, EntityManagerInterface $em): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $shelfId = $request->request->get('shelfId');

        // Vérifier si l'utilisateur a des étagères
        $library = $em->getRepository(Library::class)->findOneBy(['user' => $user]);
        if (!$library || $library->getBookshelves()->isEmpty()) {
            // S'il n'a pas d'étagères, redirige vers la page de création d'étagère
            $this->addFlash('error', 'Vous devez d\'abord créer une étagère avant d\'ajouter un livre.');
            return $this->redirectToRoute('app_library_create_shelf');  // Assure-toi que cette route existe
        }

        // Récupérer le livre et l'étagère par leurs ID
        $book = $bookRepository->find($bookId);
        $shelf = $em->getRepository(Bookshelf::class)->find($shelfId);

        // Vérification que le livre et l'étagère existent
        if (!$book || !$shelf) {
            throw $this->createNotFoundException('Le livre ou l\'étagère demandé n\'existe pas.');
        }



        // Vérifier que l'étagère appartient bien à la bibliothèque de l'utilisateur
        $library = $shelf->getLibrary();
        if ($library->getUser() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à ajouter un livre à cette étagère.');
        }

        // Ajouter le livre à l'étagère
        $shelf->addBook($book);

        // Persister les changements dans la base de données
        $em->persist($shelf);
        $em->flush();

        // Ajouter un message flash de succès et rediriger vers la bibliothèque
        $this->addFlash('success', 'Le livre a été ajouté à l\'étagère.');

        return $this->redirectToRoute('app_library');
    }

    #[Route('/library/remove-from-shelf/{bookId}/{shelfId}', name: 'app_library_remove_from_shelf', methods: ['POST'])]
    public function removeBookFromShelf(int $bookId, int $shelfId, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer la bibliothèque de l'utilisateur et l'étagère
        $shelf = $em->getRepository(Bookshelf::class)->find($shelfId);
        $book = $em->getRepository(Book::class)->find($bookId);

        if (!$shelf || !$book) {
            throw $this->createNotFoundException('L\'étagère ou le livre n\'existe pas.');
        }

        // Vérifier que l'étagère appartient à l'utilisateur
        if ($shelf->getLibrary()->getUser() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier cette étagère.');
        }

        // Retirer le livre de l'étagère
        $shelf->removeBook($book);


        $em->persist($shelf);
        $em->flush();

        $this->addFlash('success', 'Le livre a été retiré de l\'étagère.');
        return $this->redirectToRoute('app_library');
    }
    #[Route('/library/move-to-shelf/{bookId}', name: 'app_library_move_to_shelf', methods: ['POST'])]
    public function moveBookToShelf(int $bookId, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer le livre et l'étagère cible
        $book = $em->getRepository(Book::class)->find($bookId);
        $shelfId = $request->request->get('shelfId');
        $newShelf = $em->getRepository(Bookshelf::class)->find($shelfId);

        if (!$book || !$newShelf) {
            throw $this->createNotFoundException('Le livre ou l\'étagère n\'existe pas.');
        }

        // Vérifier que l'étagère cible appartient à la bibliothèque de l'utilisateur
        if ($newShelf->getLibrary()->getUser() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à déplacer un livre dans cette étagère.');
        }

        // Retirer le livre de l'étagère actuelle et l'ajouter à la nouvelle
        foreach ($book->getBookshelves() as $currentShelf) {
            $currentShelf->removeBook($book);
        }

        $newShelf->addBook($book);

        // Persister les changements
        $em->persist($newShelf);
        $em->flush();

        $this->addFlash('success', 'Le livre a été déplacé vers l\'étagère.');
        return $this->redirectToRoute('app_library');
    }
}
