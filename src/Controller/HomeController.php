<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface; 
use App\Entity\Book;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        // Récupération des livres via le repository
        $bookRepository = $entityManager->getRepository(Book::class);
        $queryBuilder = $bookRepository->createQueryBuilder('b');
        $page = $request->query->getInt('page', 1); // Récupère le numéro de page
        $books = $paginator->paginate(
            $queryBuilder,   // La requête à paginer
            $page,           // Le numéro de page
            12              // Nombre d'éléments par page
        );

        return $this->render('home/index.html.twig', [
        'books' => $books,
        
    ]);
    }
}
