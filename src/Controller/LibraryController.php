<?php

namespace App\Controller;

use App\Entity\Library;
use App\Entity\User;
use App\Entity\Book;
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
        $library = $libraryRepository->findBy(['user' => $user]);
        if (!$library) {
            return $this->render('library/empty.html.twig');
        }
        // Vérifie si la bibliothèque a des étagères
        $library = $libraryRepository->findOneBy(['user' => $user]);
        $bookshelve = $library->getBookshelves();

        return $this->render('library/index.html.twig', [
            'library' => $library,
            'bookshelve' => $bookshelve
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

        // Vérifier si le livre est déjà dans la bibliothèque de l'utilisateur
        $existingLibraryEntry = $em->getRepository(Library::class)->findOneBy(['user' => $user, 'books' => $book]);
        if ($existingLibraryEntry) {
            $this->addFlash('info', 'Le livre est déjà dans votre bibliothèque.');
            return $this->redirectToRoute('app_library');
        }

        $libraryEntry = new Library();
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

        $libraryEntry = $em->getRepository(Library::class)->findOneBy(['user' => $user, 'books' => $bookId]);
        if (!$libraryEntry) {
            throw $this->createNotFoundException('Ce livre ne fait pas partie de votre bibliothèque.');
        }
        $libraryEntry->removeBook($bookId);
        $em->remove($libraryEntry);
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
}
