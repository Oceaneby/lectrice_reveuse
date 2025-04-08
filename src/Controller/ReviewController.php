<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Book;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\EntityManagerInterface; 
use Knp\Component\Pager\PaginatorInterface; 
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;


class ReviewController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/book/{id}/reviews', name: 'app_book_reviews')]
    public function index(Book $book, ReviewRepository $reviewRepository): Response
    {
        // On récupère les commentaires associés à ce livre
        $reviews = $reviewRepository->findBy(['book' => $book], ['review_date' => 'DESC']);

        return $this->render('review/index.html.twig', [
            'book' => $book,
            'reviews' => $reviews,
        ]);
    }

    #[Route('/book/{id}/reviews/new', name: 'app_new_reviews')]
    public function new(Book $book, Request $request): Response
    {
        if (!$this->getUser()) {
            throw new AccessDeniedException('Vous devez être connecté pour laisser un commentaire.');
        }

        $review = new Review();
        $review->setBook($book);
        $review->setUser($this->getUser());
        $review->setReviewDate(new \DateTime());

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($review); 
            $this->entityManager->flush(); 

            $this->addFlash('success', 'Votre commentaire a été publié.');

            return $this->redirectToRoute('app_book_reviews', ['id' => $book->getId()]);
        }

        return $this->render('review/new.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    #[Route("/review/{id}/edit", name: "app_edit_review")]
    public function edit(Review $review, Request $request,  ReviewRepository $reviewRepository): Response
    {
        // Vérifier que l'utilisateur est connecté et qu'il est l'auteur du commentaire
        if (!$this->getUser() || $this->getUser() !== $review->getUser()) {
            throw new AccessDeniedException('Vous ne pouvez modifier que vos propres commentaires.');
        }

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            // Si la requête est AJAX, on renvoie une réponse JSON
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'success' => true,
                'reviewId' => $review->getId(),
                'reviewText' => $review->getReviewText(),
                'rating' => $review->getRating(),
                'reviewDate' => $review->getReviewDate()->format('d/m/Y H:i'),
            ]);
        }
            $this->addFlash('success', 'Votre commentaire a été mis à jour.');

            return $this->redirectToRoute('app_book_reviews', ['id' => $review->getBook()->getId()]);
        }
        $book = $review->getBook();
        $reviews = $reviewRepository->findBy(['book' => $book], ['review_date' => 'DESC']);
        $isAdmin = in_array('ROLE_ADMIN', $this->getUser()->getRoles()); 

        return $this->render('book/show.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
            'review' => $review,
            'reviews' => $reviews,
            'isAdmin' => $isAdmin, 
            'reviewToEdit' => $review, 
          
        ]);
    }
    #[Route("/review/{id}/delete", name: "app_delete_review", methods: ["POST"])]
    public function delete(Review $review, Request $request): Response
    {
        if (!$review) {
            throw $this->createNotFoundException('La revue demandée n\'existe pas.');
        }
        
        if (!$this->getUser() || $this->getUser() !== $review->getUser()) {
            throw new AccessDeniedException('Vous ne pouvez supprimer que vos propres commentaires.');
        }

        if ($this->isCsrfTokenValid('delete' . $review->getId(), $request->headers->get('X-CSRF-TOKEN'))) {
          
            $this->entityManager->remove($review);
            $this->entityManager->flush();
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['success' => true]);
            }

            $this->addFlash('success', 'Votre commentaire a été supprimé.');
            return $this->redirectToRoute('app_book_reviews', ['id' => $review->getBook()->getId()]);
        }
        return new JsonResponse(['success' => false, 'message' => 'Token CSRF invalide.']);
        
    }
    #[Route("/profile/reviews", name: "profile_edit_reviews")]
    public function editReviews( ReviewRepository $reviewRepository, Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
{
    $user = $this->getUser();
    // Récupérer tous les commentaires de l'utilisateur
    $reviewsQuery = $reviewRepository->createQueryBuilder('r')
        ->where('r.user = :user')
        ->setParameter('user', $user)
        ->orderBy('r.review_date', 'DESC'); // Tri par date décroissante

    $page = $request->query->getInt('page', 1); // Récupère le numéro de page

    $reviews = $paginator->paginate(
        $reviewsQuery,   // La requête triée
        $page,           // Le numéro de page
        5                // Nombre d'éléments par page
    );

    return $this->render('review/edit.html.twig', [
        'reviews' => $reviews,
    ]);
}

    #[Route("/profile/reviews/{id}/edit", name: "profile_edit_review", methods: ["POST", "GET"])]
    public function editReview(Review $review, Request $request): Response
{
    // Vérifie que l'utilisateur est bien l'auteur du commentaire
    if ($this->getUser() !== $review->getUser()) {
        throw new AccessDeniedException('Vous ne pouvez modifier que vos propres commentaires.');
    }

    $form = $this->createForm(ReviewType::class, $review);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $this->entityManager->flush();
        $this->addFlash('success', 'Votre commentaire a été mis à jour.');
        return $this->redirectToRoute('profile_edit_reviews');
    }

    return $this->render('review/review_edit.html.twig', [
        'form' => $form->createView(),
        'review' => $review,
    ]);
}

}