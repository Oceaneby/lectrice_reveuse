<?php


namespace App\Controller;

use App\Entity\User;
use App\Entity\ProfilPicture;
use App\Form\UserType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/profile')]
class ProfilController extends AbstractController
{
    #[Route(name: 'app_profile_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, BookRepository $bookRepository, Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createNotFoundException("L'utilisateur n'est pas connecté ou n'existe pas.");
        }
        $bookshelves = $user->getBookshelves();

        $paginatedBooksByShelf = [];
        foreach ($bookshelves as $bookshelf) {
            // On récupère tous les livres de l'étagère, puis on les pagine
            $query = $bookRepository->createQueryBuilder('b')
                ->innerJoin('b.bookshelves', 'bs')
                ->where('bs = :shelf') 
                ->setParameter('shelf', $bookshelf)
                ->getQuery();
            
            // On pagine les livres de cette étagère
            $paginatedBooksByShelf[$bookshelf->getId()] = $paginator->paginate(
                $query, // La requête qui récupère les livres de l'étagère
                $request->query->getInt('page', 1), // Numéro de page
                5 
            );
        }
        // On envoie les livres paginés par étagère à la vue
        return $this->render('profil/profile.html.twig', [
            'paginatedBooksByShelf' => $paginatedBooksByShelf,
            'user' => $user,
            'bookshelves' => $bookshelves,
        ]);
    }

    // Modifier son propre profil
    #[Route('/edit', name: 'profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser(); 
        // dd($user);

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Créer le formulaire pour la modification du profil
        $form = $this->createForm(UserType::class, $user);
            // 'is_admin' => false, // Ne permet pas de modifier les rôles
            // 'is_password_changeable' => true, // Permet de modifier le mot de passe
        

        $form->handleRequest($request);
      
    if ($form->isSubmitted()) {
        // Vérification si le champ first_name est vide ou null après soumission
        if ($user->getFirstName() === null || trim($user->getFirstName() === '')) {
            $this->addFlash('error', 'Le prénom est requis.');
            return $this->redirectToRoute('profile_edit');

        }

        // Si le formulaire est valide, on procède à la mise à jour
        if ($form->isValid()) {
            if ($user->getFirstName() === null) {
                $user->setFirstName('');  // Définit une chaîne vide si le champ est null
            }

            // Gestion du mot de passe et de la validation
            $oldPassword = $form->get('oldPassword')->getData();
            $password = $form->get('password')->getData();
            $confirmPassword = $form->get('ConfirmPassword')->getData();

            if ($password || $oldPassword || $confirmPassword) {
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

                if ($password !== $confirmPassword) {
                    $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                    return $this->redirectToRoute('profile_edit');
                }

                // Mise à jour du mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
            }

            /** @var UploadedFile $file */
            $file = $form->get('profilPicture')->getData();

            if ($file) {
                // Gestion de l'upload de l'image de profil
                $profilPicture = new ProfilPicture();
                $filename = uniqid() . '.' . $file->guessExtension();
                $fileExtension = $file->guessExtension();
                $fileSize = $file->getSize();

                try {
                    $file->move(
                        $this->getParameter('profil_pictures_directory'),
                        $filename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur s\'est produite lors de l\'upload du fichier.');
                    return $this->redirectToRoute('profile_edit');
                }

                $profilPicture->setImageUrl($filename);
                $profilPicture->setUser($user);
                $profilPicture->setFileSize($fileSize);
                $profilPicture->setFileFormat($fileExtension);
                $profilPicture->setUploadDate(new \DateTime());

                // Si un profil existe déjà, on le retire avant d'ajouter le nouveau
                if ($user->getProfilPicture()->count() > 0) {
                    $oldProfilPicture = $user->getProfilPicture()->first();
                    $user->removeProfilPicture($oldProfilPicture);
                    $entityManager->remove($oldProfilPicture);
                }

                $user->addProfilPicture($profilPicture);
                $entityManager->persist($profilPicture);
            }

            // Sauvegarder l'utilisateur
            $entityManager->persist($user);
            $entityManager->flush();

            // Message de succès
            $this->addFlash('success', 'Vos informations ont été mises à jour.');
            return $this->redirectToRoute('app_home');
        } else {
            // Si le formulaire n'est pas valide, afficher les erreurs
            foreach ($form->all() as $field) {
                foreach ($field->getErrors() as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }
    }

    // Rendu du formulaire dans la vue
    return $this->render('profil/index.html.twig', [
        'form' => $form->createView(),
        'user' => $user,
    ]);
    }
    
    
}
