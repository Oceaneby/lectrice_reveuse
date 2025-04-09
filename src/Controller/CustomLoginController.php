<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

class CustomLoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        Security $security
    ): Response {
        $form = $this->createForm(LoginFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user = $entityManager->getRepository(User::class)->findOneBy([
                'email' => $data['email']
            ]);

            if (!$user || !$passwordHasher->isPasswordValid($user, $data['password'])) {
                $this->addFlash('error', 'Identifiants incorrects.');
            } else {
                // 🔥 Connexion manuelle (Symfony 7)
                $security->login($user, 'form_login'); // le 2e param peut être un nom d’authenticator

                $this->addFlash('success', 'Connexion réussie !');
                return $this->redirectToRoute('app_home'); // à adapter selon ta route home
            }
        }

        return $this->render('security/login.html.twig', [
            'loginForm' => $form,
        ]);
    }
}
