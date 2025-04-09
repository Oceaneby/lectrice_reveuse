<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;



class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // dump($form->getData());

        if ($form->isSubmitted()) {
            // dd("ta maman quoi");

            // dd($form->getErrors(true, false));
if($form->isValid()) {

   $plainPassword = $form->get('plainPassword')->getData();

   // encode the plain password
   $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

   $user->setRoles(['ROLE_USER']); 

   $user->setRegistrationDate(new \DateTime()); 

   $birthDate = $user->getBirthDate();
   if ($birthDate) {
       // Assurer que la date est bien dans un format compatible (si nécessaire)
       $formattedBirthDate = $birthDate->format('Y-m-d'); 
       $user->setBirthDate(new \DateTime($formattedBirthDate));  // Ici, la date est de nouveau transformée en objet DateTime si nécessaire
   }
   dump($form->getErrors(true));

   $entityManager->persist($user);
   $entityManager->flush();
   $this->addFlash('success', 'Votre inscription est réussie!');
   // do anything else you need here, like send an email
   $security->login($user, 'form_login', 'main');
   return $this->redirectToRoute('app_home');
}
         
          } else {
            dump($form->getErrors(true));
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
