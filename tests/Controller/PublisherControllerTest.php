<?php

namespace App\Tests\Controller;

use App\Entity\Publisher;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PublisherControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $publisherRepository;
    private string $path = '/publisher/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->publisherRepository = $this->manager->getRepository(Publisher::class);

        foreach ($this->publisherRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Publisher index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'publisher[name]' => 'Testing',
            'publisher[address]' => 'Testing',
            'publisher[website]' => 'Testing',
            'publisher[foundation_year]' => 'Testing',
            'publisher[description]' => 'Testing',
            'publisher[logo]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->publisherRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Publisher();
        $fixture->setName('My Title');
        $fixture->setAddress('My Title');
        $fixture->setWebsite('My Title');
        $fixture->setFoundation_year('My Title');
        $fixture->setDescription('My Title');
        $fixture->setLogo('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Publisher');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Publisher();
        $fixture->setName('Value');
        $fixture->setAddress('Value');
        $fixture->setWebsite('Value');
        $fixture->setFoundation_year('Value');
        $fixture->setDescription('Value');
        $fixture->setLogo('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'publisher[name]' => 'Something New',
            'publisher[address]' => 'Something New',
            'publisher[website]' => 'Something New',
            'publisher[foundation_year]' => 'Something New',
            'publisher[description]' => 'Something New',
            'publisher[logo]' => 'Something New',
        ]);

        self::assertResponseRedirects('/publisher/');

        $fixture = $this->publisherRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getAddress());
        self::assertSame('Something New', $fixture[0]->getWebsite());
        self::assertSame('Something New', $fixture[0]->getFoundation_year());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getLogo());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Publisher();
        $fixture->setName('Value');
        $fixture->setAddress('Value');
        $fixture->setWebsite('Value');
        $fixture->setFoundation_year('Value');
        $fixture->setDescription('Value');
        $fixture->setLogo('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/publisher/');
        self::assertSame(0, $this->publisherRepository->count([]));
    }
}
