<?php

namespace App\Tests\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Publisher;
use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use App\Repository\PublisherRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class BookControllerTest extends WebTestCase
{
    public function testBookIndexPage(): void
    {
        // Créer un client de test
        $client = static::createClient();
        
        // Accéder à la page d'index des livres
        $client->request('GET', '/book');
        
        // Vérifier que la réponse est OK (200)
        $this->assertResponseIsSuccessful();
        
        // Vérifier que le titre de la page est bien présent
        $this->assertSelectorTextContains('h1', 'Liste des livres');
    }

    public function testBookCreate(): void
    {
        // Créer un client de test
        $client = static::createClient();
        
        // Se connecter en tant qu'administrateur ou utilisateur autorisé (selon la configuration de ton système de sécurité)
        $client->loginUser($this->createAdminUser());

        // Accéder à la page de création d'un livre
        $client->request('GET', '/book/new');
        
        // Vérifier que la page de création est accessible
        $this->assertResponseIsSuccessful();

        // Préparer les données du formulaire pour un livre
        $formData = [
            'book[title]' => 'Livre de Test',
            'book[description]' => 'Une description de test pour le livre.',
            'book[isbn]' => '978-1234567890',
            'book[publication_date]' => '2025-04-04',
            'book[cover_image]' => null, // Test sans image
            'book[authors]' => [1], // Ajouter un auteur existant, ID de l'auteur (assure-toi que l'auteur existe en base de données)
            'book[genres]' => [1],  // Ajouter un genre existant, ID du genre
            'book[publisher]' => 1,  // Ajouter un éditeur existant
        ];

        // Soumettre le formulaire de création
        $client->request('POST', '/book/new', $formData);

        // Vérifier que la redirection se fait vers la page d'index des livres après la soumission du formulaire
        $this->assertResponseRedirects('/book', Response::HTTP_SEE_OTHER);

        // Vérifier si le livre a bien été ajouté à la base de données
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $book = $bookRepository->findOneBy(['title' => 'Livre de Test']);
        $this->assertNotNull($book);
    }

    public function testBookShow(): void
    {
        // Créer un client de test
        $client = static::createClient();
        
        // Trouver un livre existant
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $book = $bookRepository->findOneBy(['title' => 'Livre de Test']);
        
        // Accéder à la page de détail du livre
        $client->request('GET', '/book/' . $book->getId());
        
        // Vérifier que la page est bien accessible
        $this->assertResponseIsSuccessful();
        
        // Vérifier la présence du titre du livre sur la page
        $this->assertSelectorTextContains('h1', $book->getTitle());
    }

    public function testBookEdit(): void
    {
        // Créer un client de test
        $client = static::createClient();
        
        // Se connecter en tant qu'administrateur ou utilisateur autorisé
        $client->loginUser($this->createAdminUser());

        // Trouver un livre existant
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $book = $bookRepository->findOneBy(['title' => 'Livre de Test']);
        
        // Accéder à la page d'édition du livre
        $client->request('GET', '/book/' . $book->getId() . '/edit');
        
        // Vérifier que la page d'édition est accessible
        $this->assertResponseIsSuccessful();

        // Préparer les nouvelles données pour modifier le livre
        $formData = [
            'book[title]' => 'Livre Modifié',
            'book[description]' => 'Une nouvelle description.',
            'book[isbn]' => '978-9876543210',
            'book[publication_date]' => '2025-05-05',
            'book[cover_image]' => null, // Test sans image
            'book[authors]' => [1], // Ajouter un auteur existant
            'book[genres]' => [1],  // Ajouter un genre existant
            'book[publisher]' => 1,  // Ajouter un éditeur existant
        ];

        // Soumettre le formulaire d'édition
        $client->request('POST', '/book/' . $book->getId() . '/edit', $formData);

        // Vérifier que la redirection se fait vers la page de détail du livre après la soumission du formulaire
        $this->assertResponseRedirects('/book/' . $book->getId(), Response::HTTP_SEE_OTHER);
        
        // Vérifier que les informations du livre ont bien été modifiées
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $book = $bookRepository->findOneBy(['title' => 'Livre Modifié']);
        $this->assertNotNull($book);
        $this->assertSame('Livre Modifié', $book->getTitle());
    }

    public function testBookDelete(): void
    {
        // Créer un client de test
        $client = static::createClient();
        
        // Se connecter en tant qu'administrateur ou utilisateur autorisé
        $client->loginUser($this->createAdminUser());

        // Trouver un livre existant
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $book = $bookRepository->findOneBy(['title' => 'Livre Modifié']);
        
        // Accéder à la page de suppression du livre
        $client->request('POST', '/book/' . $book->getId() . '/delete');
        
        // Vérifier que la redirection se fait vers la page d'index des livres après la suppression
        $this->assertResponseRedirects('/book', Response::HTTP_SEE_OTHER);
        
        // Vérifier que le livre a bien été supprimé de la base de données
        $book = $bookRepository->findOneBy(['title' => 'Livre Modifié']);
        $this->assertNull($book);
    }

    private function createAdminUser()
    {
        // Créer un utilisateur administrateur pour les tests de connexion
        $user = new \App\Entity\User();
        $user->setUsername('admin')
             ->setPassword('password')
             ->setRoles(['ROLE_ADMIN']);
        
        return $user;
    }
}