<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PolitiquesDeConfidentialiteController extends AbstractController
{
    #[Route('/politiques/de/confidentialite', name: 'app_politiques_de_confidentialite')]
    public function index(): Response
    {
        return $this->render('politiques_de_confidentialite/index.html.twig', [
            'controller_name' => 'PolitiquesDeConfidentialiteController',
        ]);
    }
}
