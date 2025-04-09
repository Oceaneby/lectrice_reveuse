<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        Security $security,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode le mot de passe
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Définir les rôles par défaut
            $user->setRoles(['ROLE_USER']);

            // Date d'inscription
            $user->setRegistrationDate(new \DateTime());

            // Formatage et validation de la date de naissance
            $birthDate = $user->getBirthDate();
            if ($birthDate instanceof \DateTimeInterface) {
                $user->setBirthDate($birthDate); // pas besoin de retransformer si déjà DateTime
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre inscription est réussie!');

            // ⚠️ Note : Security::login() nécessite un Authenticator (Symfony 6+)
            // Tu dois créer un LoginAuthenticator si tu veux login automatiquement
            // Ici on redirige simplement
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
