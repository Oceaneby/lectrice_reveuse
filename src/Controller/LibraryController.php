<?php

namespace App\Controller;

use App\Entity\Library;
use App\Entity\User;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\LibraryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/library')]
final class LibraryController extends AbstractController
{
    #[Route( name: 'app_library')]
    public function viewLibrary(LibraryRepository $libraryRepository): Response
    {
        $user = $this->getUser();

        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $library = $libraryRepository->findOneBy(['user' => $user]);
        if(!$library){
            return $this->render('library/empty.html.twig');
        }
        return $this->render('library/index.html.twig', [
            'library' => $library,
        ]);
    }

     // Ajouter un livre à la bibliothèque
     #[Route('/add', name: 'app_library_add', methods: ['GET', 'POST'])]
     public function addBook(Request $request, LibraryRepository $libraryRepository, EntityManagerInterface $em): Response
     {
         $user = $this->getUser();
         
         if (!$user) {
             return $this->redirectToRoute('app_login');
         }
         
         $library = $libraryRepository->findOneBy(['user' => $user]);
 
         if (!$library) {
             // Créer une nouvelle bibliothèque si elle n'existe pas
             $library = new Library();
             $library->setUser($user);
             $em->persist($library);
             $em->flush();
         }
 
         $book = new Book();
         $book->setLibrary($library);
 
         // Tu pourrais ajouter d'autres champs comme le titre, l'auteur, etc.
         $form = $this->createFormBuilder($book)
             ->add('title')
             ->add('author')
             ->getForm();
 
         if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
             $em->persist($book);
             $em->flush();
 
             $this->addFlash('success', 'Le livre a été ajouté avec succès.');
             return $this->redirectToRoute('app_library');
         }
 
         return $this->render('library/add.html.twig', [
             'form' => $form->createView(),
         ]);
     }
 
     // Supprimer un livre
     #[Route('/delete/{id}', name: 'app_library_delete', methods: ['POST'])]
     public function deleteBook(Book $book, EntityManagerInterface $em): Response
     {
         if ($book->getLibrary()->getUser() !== $this->getUser()) {
             throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer ce livre.');
         }
 
         $em->remove($book);
         $em->flush();
 
         $this->addFlash('success', 'Le livre a été supprimé.');
         return $this->redirectToRoute('app_library');
     }
}
