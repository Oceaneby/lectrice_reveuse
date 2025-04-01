<?php
namespace App\Tests\Entity;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Genre; 
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BookTest extends TestCase
{
    public function testSetAndGetTitle()
    {
        $book = new Book();
        $book->setTitle("Test Book");

        $this->assertEquals("Test Book", $book->getTitle());
    }

    public function testSetAndGetDescription()
    {
        $book = new Book();
        $book->setDescription("Test description");

        $this->assertEquals("Test description", $book->getDescription());
    }

    public function testSetCoverImageWithUploadedFile()
    {
          // Crée un mock d'UploadedFile
    $file = $this->createMock(UploadedFile::class);

    // Simule le comportement de la méthode getClientOriginalName
    $file->method('getClientOriginalName')
         ->willReturn('test-cover-image.jpg');

    // Crée un objet Book
    $book = new Book();

    // Appelle la méthode setCoverImage sur l'objet Book avec le mock
    $book->setCoverImage($file);

    // Vérifie que le nom du fichier est bien celui attendu
    $this->assertEquals('test-cover-image.jpg', $book->getCoverImage());
    }

    public function testAddAndRemoveAuthor()
    {
        $book = new Book();

    // Créer un vrai objet Author
    $author = new Author();
    // Donne un nom ou autre propriété nécessaire pour l'Author
    $author->setFirstName("John");
    $author->setLastName("Doe");

    // Test addAuthor
    $book->addAuthor($author);
    $this->assertCount(1, $book->getAuthors());

    // Test removeAuthor
    $book->removeAuthor($author);
    $this->assertCount(0, $book->getAuthors());
    }

    public function testAddAndRemoveGenre()
    {
        $book = new Book();
        $genre = new Genre();
       
        // Test addGenre
        $book->addGenre($genre);
        $this->assertCount(1, $book->getGenres());

        // Test removeGenre
        $book->removeGenre($genre);
        $this->assertCount(0, $book->getGenres());
    }
    public function testPremierEchecVolontaire()
    {
        $book = new Book();
        $book->setTitle("Test d'un échec volontaire sur un livre");

        // Forcer un échec volontaire
        $this->fail("Test Échec volontaire");

        // Cette ligne ne sera jamais atteinte, car l'échec aura interrompu le test ci-dessus
        $this->assertEquals("Test d'un échec volontaire sur un livre", $book->getTitle());
    }
}

