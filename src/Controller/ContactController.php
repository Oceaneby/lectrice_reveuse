<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/contact')]
class ContactController extends AbstractController
{
    #[Route(name: 'app_contact', methods: ['GET'])]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        // Créer une instance du formulaire Contact
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            $data = $form->getData();
            $emailAddress = $data['email'];
            $message = $data['message']; 

            // Ajouter un message flash pour confirmer l'envoi
            $this->addFlash('success', 'Your message has been sent successfully!');

            // Rediriger vers la page de contact
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}