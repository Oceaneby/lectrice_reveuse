<?php


namespace App\Controller;

use App\Entity\User;
use App\Entity\ProfilPicture;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/profile')]
class ProfilController extends AbstractController
{
    #[Route(name: 'app_profile_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        return $this->render('profil/profile.html.twig', [
            'user' => $user,
        ]);
    }
    // Modifier son propre profil
    #[Route('/edit', name: 'profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser(); // Récupérer l'utilisateur connecté
        // dd($user);

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Créer le formulaire pour la modification du profil
        $form = $this->createForm(UserType::class, $user);
            // 'is_admin' => false, // Ne permet pas de modifier les rôles
            // 'is_password_changeable' => true, // Permet de modifier le mot de passe
        

        $form->handleRequest($request);
      
        // dd('Form is being handled');
        // dd($form->getData());

        if ($form->isSubmitted() && $form->isValid()) {
            // dd('Form submitted and valid');
            // Vérifier si un fichier a été téléchargé
            $oldPassword = $form->get('oldPassword')->getData();
            $password = $form->get('password')->getData();
            $ConfirmPassword = $form->get('ConfirmPassword')->getData();

            if (($password || $oldPassword) || $ConfirmPassword) {

                if (!$oldPassword) {
                    $this->addFlash('error', 'Veuillez renseigner votre ancien mot de passe.');
                    return $this->redirectToRoute('profile_edit');
                 }

                if (!$password) {
                    $this->addFlash('error', 'Veuillez renseigner un nouveau mot de passe.');
                    return $this->redirectToRoute('profile_edit');
                }

                if ($oldPassword === $password) {
                    $this->addFlash('error', 'Le nouveau mot de passe doit être différent de l\'ancien.');
                    return $this->redirectToRoute('profile_edit');
                }

                if (!$passwordHasher->isPasswordValid($user, $oldPassword)) {
                    $this->addFlash('error', 'Ancien mot de passe incorrect.');
                    return $this->redirectToRoute('profile_edit');
                }

                if (($oldPassword && $password) && !$ConfirmPassword) {
                    $this->addFlash('error', 'Veuillez confirmer votre nouveau mot de passe.');
                    return $this->redirectToRoute('profile_edit');
                }
                
                if ($password !== $ConfirmPassword) {
                    $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                    return $this->redirectToRoute('profile_edit');
                }
              

            // Si tout est correct, on met à jour le mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
            }
    
        
        //  dd($form->getErrors(true));

          
            /** @var UploadedFile $file */
            
            $file = $form->get('profilPicture')->getData();
            
            if ($file && file_exists($file->getPathname())) {
                // Créer une nouvelle instance de ProfilPicture et enregistrer l'image
                $profilPicture = new ProfilPicture();
                $filename = uniqid() . '.' . $file->guessExtension(); // Générer un nom unique pour le fichier
                $fileExtension = $file->guessExtension();
                $fileSize =$file->getSize();

                $file->move(
                    $this->getParameter('profil_pictures_directory'), // Définir le répertoire pour stocker l'image
                    $filename
                );
                // dd($file);
                $profilPicture->setImageUrl($filename);  // Utiliser setImageUrl pour enregistrer le nom de l'image
                $profilPicture->setUser($user);  // Lier la photo de profil à l'utilisateur

                $profilPicture->setFileSize($fileSize);  // Optionnel : Enregistrer la taille du fichier

                $profilPicture->setFileFormat($fileExtension);  // Optionnel : Enregistrer le format du fichier


                $profilPicture->setUploadDate(new \DateTime());  // Optionnel : Enregistrer la date de téléchargement
                $entityManager->persist($profilPicture); // Enregistrer la photo dans la base de données
                
                $user->setProfilPicture($profilPicture);
            }
    
            // Sauvegarder l'utilisateur
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Vos informations ont été mises à jour.');
            return $this->redirectToRoute('app_home');
        }
    
    
        return $this->render('profil/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
