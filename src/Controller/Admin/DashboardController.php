<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Bookshelf;
use App\Entity\DefaultAvatar;
use App\Entity\Genre;
use App\Entity\Library;
use App\Entity\ProfilPicture;
use App\Entity\Publisher;
use App\Entity\Review;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Controller\Trait\Show;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
  
    public function index(): Response
    {
    
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(AuthorCrudController::class)->generateUrl());

       
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Lectrice Reveuse');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Les Auteurs', 'fas fa-users', Author::class);
        yield MenuItem::linkToCrud('Les Utilisateurs', 'fas fa-user-cog', User::class);
        yield MenuItem::linkToCrud('Les commentaires', 'fas fa-comment', Review::class);
        yield MenuItem::linkToCrud('Les bibliothèques', 'fas fa-Book', Library::class);
        yield MenuItem::linkToCrud('Les étagères de bibliothèques', 'fas fa-cabinet', Bookshelf::class);
        yield MenuItem::linkToCrud('Les livres', 'fas fa-book-reader', Book::class);
        yield MenuItem::linkToCrud('Les genres', 'fas fa-tags', Genre::class);
        yield MenuItem::linkToCrud('Les maison d\'éditions ', 'fas fa-building', Publisher::class);
        yield MenuItem::linkToCrud('Les photos de profil', 'fas fa-camera', ProfilPicture::class);
        yield MenuItem::linkToCrud('Image par défault', 'fas fa-images', DefaultAvatar::class);
    
    }
}
