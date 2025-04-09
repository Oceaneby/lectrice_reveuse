<?php

namespace App\Controller;

use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class CustomLoginController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(
        Request $request,
        UserAuthenticatorInterface $authenticator,
        FormLoginAuthenticator $formLoginAuthenticator
    ): Response {
        $form = $this->createForm(LoginFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Les champs sont déjà validés, y compris le CSRF
            $data = $form->getData();

            // Symfony gère la connexion ici
            return $authenticator->authenticateUser(
                $this->getUser(), // déjà chargé automatiquement
                $formLoginAuthenticator,
                $request
            );
        }

        return $this->render('security/login.html.twig', [
            'loginForm' => $form,
        ]);
    }
}
