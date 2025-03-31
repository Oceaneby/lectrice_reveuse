<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Review;
use App\Form\BookType;
use App\Form\BookSearchType;
use App\Form\ReviewType;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\File;

#[Route('/book')]
final class BookController extends AbstractController
{
    
    #[Route(name: 'app_book_index', methods: ['GET'])]
    public function index(Request $request, BookRepository $bookRepository): Response
    {
         // Création du formulaire de recherche
         $form = $this->createForm(BookSearchType::class);
         $form->handleRequest($request);
 
         // Récupérer les résultats filtrés
         $books = $bookRepository->searchBooks($form->getData()['query'] ?? '');

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'form' => $form->createView(),
        ]);
    }

    // Nouvelle route pour la recherche AJAX
    #[Route('/search', name: 'app_book_search', methods: ['GET'])]
    public function search(Request $request, BookRepository $bookRepository): Response
    {
        $query = $request->query->get('query');
        $results = [];
        
        if ($query) {
            // Recherche les livres
            $books = $bookRepository->searchBooks($query);
    
            // Vérifie si des livres sont trouvés
            dump($books);  
    
            // Convertir les livres en un format simple
            foreach ($books as $book) {
                $results[] = [
                    'id' => $book->getId(),
                    'title' => $book->getTitle(),
                    'author' => $book->getAuthors(),
                ];
            }
        }
        return new JsonResponse($results);  
    }
    
    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cover_image = $form->get('cover_image')->getData();

            if ($cover_image) {
                // Créer un nom de fichier unique basé sur le slug du titre et un identifiant unique
                $originalFilename = pathinfo($cover_image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cover_image->guessExtension();
                try {
                    // Déplace le fichier dans le répertoire 'uploads/book_cover/'
                    $cover_image->move(
                        $this->getParameter('book_cover_directory'), // Configurer le répertoire dans services.yaml
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'erreur si l'image ne peut pas être déplacée
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image.');
                    return $this->redirectToRoute('book_new');
                }
                // Enregistrer le nom du fichier dans la base de données
                $book->setCoverImage($newFilename);
            }

            // Associer les auteurs sélectionnés
            $authors = $form->get('authors')->getData(); // On récupère les auteurs sélectionnés
            // dump($authors); 
            // dd($authors);
            foreach ($authors as $author) {
            $book->addAuthor($author);  
            $entityManager->persist($author); 
        }
        foreach ($book->getAuthors() as $author) {
            // dd($book->getAuthors());
        }

            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_show', methods: ['GET', 'POST'])]
    public function show(int $id, BookRepository $bookRepository, Book $book, ReviewRepository $reviewRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $reviews = $reviewRepository->findBy(['book' => $book], ['review_date' => 'DESC']);
         // Création du formulaire de commentaire
         $review = new Review();
         $form = $this->createForm(ReviewType::class, $review);
         $form->handleRequest($request);
 
         if ($form->isSubmitted() && $form->isValid()) {
             // Associer le livre et l'utilisateur au commentaire
             $review->setBook($book);
             $review->setUser($this->getUser()); 
             $review->setReviewDate(new \DateTime());
 
             // Enregistrer la revue dans la base de données
             $entityManager->persist($review);
             $entityManager->flush();
 
             // Ajouter un message flash pour signaler le succès
             $this->addFlash('success', 'Commentaire ajouté avec succès!');
             return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
         }
 
         // Vérification du rôle de l'utilisateur
         $isAdmin = $this->isGranted('ROLE_ADMIN');  
 
         return $this->render('book/show.html.twig', [
             'book' => $book,
             'isAdmin' => $isAdmin, 
             'reviews' => $reviews, 
             'form' => $form->createView(), 
         ]);
    }

    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        // dump($book->getId());
      
    $cover_imagePath = $book->getCoverImage();
    if ($cover_imagePath) {
        $cover_imageFile = new File($this->getParameter('book_cover_directory') . '/' . $cover_imagePath);
        
        // Remettre ce fichier dans le formulaire
        $form = $this->createForm(BookType::class, $book);
        $form->get('cover_image')->setData($cover_imageFile);
    } else {
        // Créer le formulaire si aucun fichier n'est trouvé
        $form = $this->createForm(BookType::class, $book);
    }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cover_image = $form->get('cover_image')->getData();
            if ($cover_image) {
                // Créer un nom de fichier unique basé sur le slug du titre et un identifiant unique
                $originalFilename = pathinfo($cover_image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cover_image->guessExtension();
                try {
                    $cover_image->move(
                        $this->getParameter('book_cover_directory'), 
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image.');
                    return $this->redirectToRoute('book_new');
                }
                $book->setCoverImage($newFilename);
            }

            $entityManager->persist($book);
            $entityManager->flush();
           

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_book_index');
    }
}
