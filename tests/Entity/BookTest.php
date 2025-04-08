
<!-- namespace App\Tests\Entity;

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
    $file = $this->createMock(UploadedFile::class);

   
    $file->method('getClientOriginalName')
         ->willReturn('test-cover-image.jpg');

   
    $book = new Book();

    
    $book->setCoverImage($file);

    $this->assertEquals('test-cover-image.jpg', $book->getCoverImage());
    }

    public function testAddAndRemoveAuthor()
    {
        $book = new Book();

        $author = new Author();
        $author->setFirstName("John");
        $author->setLastName("Doe");

        $book->addAuthor($author);
        $this->assertCount(1, $book->getAuthors());

        $book->removeAuthor($author);
        $this->assertCount(0, $book->getAuthors());
    }

    public function testAddAndRemoveGenre()
    {
        $book = new Book();
        $genre = new Genre();
       
   
        $book->addGenre($genre);
        $this->assertCount(1, $book->getGenres());

        $book->removeGenre($genre);
        $this->assertCount(0, $book->getGenres());
    }
    public function testPremierEchecVolontaire()
    {
        $book = new Book();
        $book->setTitle("Test d'un échec volontaire sur un livre");

        $this->fail("Test Échec volontaire");

        $this->assertEquals("Test d'un échec volontaire sur un livre", $book->getTitle());
    }
} -->
