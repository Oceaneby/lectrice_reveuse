<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function searchBooks(string $query)
    {
        // Recherche les livres dont le titre ou l'auteur contient la requête
        return $this->createQueryBuilder('b')
                    ->where('b.title LIKE :query')
                    ->setParameter('query', $query . '%')  // Recherche de titre commençant par la requête
                    ->getQuery()
                    ->getResult();
    }
   
}

