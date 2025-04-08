<?php

namespace App\Tests\Controller;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AuthorControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $authorRepository;
    private string $path = '/author/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->authorRepository = $this->manager->getRepository(Author::class);

        foreach ($this->authorRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Author index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'author[first_name]' => 'Testing',
            'author[last_name]' => 'Testing',
            'author[biography]' => 'Testing',
            'author[author_picture]' => 'Testing',
            'author[birth_date]' => 'Testing',
            'author[nationality]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->authorRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Author();
        $fixture->setFirst_name('My Title');
        $fixture->setLast_name('My Title');
        $fixture->setBiography('My Title');
        $fixture->setAuthor_picture('My Title');
        $fixture->setBirth_date('My Title');
        $fixture->setNationality('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Author');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Author();
        $fixture->setFirst_name('Value');
        $fixture->setLast_name('Value');
        $fixture->setBiography('Value');
        $fixture->setAuthor_picture('Value');
        $fixture->setBirth_date('Value');
        $fixture->setNationality('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'author[first_name]' => 'Something New',
            'author[last_name]' => 'Something New',
            'author[biography]' => 'Something New',
            'author[author_picture]' => 'Something New',
            'author[birth_date]' => 'Something New',
            'author[nationality]' => 'Something New',
        ]);

        self::assertResponseRedirects('/author/');

        $fixture = $this->authorRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getFirst_name());
        self::assertSame('Something New', $fixture[0]->getLast_name());
        self::assertSame('Something New', $fixture[0]->getBiography());
        self::assertSame('Something New', $fixture[0]->getAuthor_picture());
        self::assertSame('Something New', $fixture[0]->getBirth_date());
        self::assertSame('Something New', $fixture[0]->getNationality());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Author();
        $fixture->setFirst_name('Value');
        $fixture->setLast_name('Value');
        $fixture->setBiography('Value');
        $fixture->setAuthor_picture('Value');
        $fixture->setBirth_date('Value');
        $fixture->setNationality('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/author/');
        self::assertSame(0, $this->authorRepository->count([]));
    }
}
