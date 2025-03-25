<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Form\BookSearchType;
use App\Repository\BookRepository;
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
         $books = $bookRepository->findBySearch($form->getData());

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
            dump($books);  // Utilise dump() pour voir les résultats de la recherche
    
            // Convertir les livres en un format simple
            foreach ($books as $book) {
                $results[] = [
                    'id' => $book->getId(),
                    'title' => $book->getTitle(),
                    'author' => $book->getAuthor(),
                ];
            }
        }
        // $this->get('logger')->info('Search Results:', ['results' => $results]);
        return new JsonResponse($results);  // Retourner les résultats au format JSON
    }
    

    

    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $cover_image = $book->getCoverImage();
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

            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(int $id, BookRepository $bookRepository, Book $book): Response
    {
        $book = $bookRepository->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Livre non trouvé');
        }
         // Vérification du rôle de l'utilisateur
        $isAdmin = $this->isGranted('ROLE_ADMIN'); // Vérifie si l'utilisateur a le rôle d'admin

        return $this->render('book/show.html.twig', [
            'book' => $book,
            'isAdmin' => $isAdmin, // On passe la variable isAdmin à la vue
        ]);
    }

    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        // dump($book->getId());
        
        // Exemple d'ajout d'un fichier existant dans le formulaire
    $cover_imagePath = $book->getCoverImage(); // Récupérer le nom du fichier enregistré en base de données
    if ($cover_imagePath) {
        // Créer un objet File avec le chemin du fichier
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
            // $cover_image = $book->getCoverImage();
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
