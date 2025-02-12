<?php

namespace App\Entity;

use App\Repository\LibraryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: LibraryRepository::class)]
class Library
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'libraries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'libraries')]
    private Collection $book;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $added_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $book_status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $end_date = null;

    /**
     * @var Collection<int, >
     */
    #[ORM\OneToMany(targetEntity: Bookshelf::class, mappedBy: 'library')]
    private Collection $bookshelve;

    public function __construct()
    {
        $this->book = new ArrayCollection();
        $this->bookshelve = new ArrayCollection(); // Initialiser la collection de livres
    }

    public function getBookshelves(): Collection
    {
        return $this->bookshelve;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getBook(): Collection
    {
        return $this->book;
    }

    // Ajouter un livre à la bibliothèque
    public function addBook(Book $book): static
    {
        if (!$this->book->contains($book)) {
            $this->book[] = $book;
        }

        return $this;
    }

    // Supprimer un livre de la bibliothèque
    public function removeBook(Book $book): static
    {
        $this->book->removeElement($book);

        return $this;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getAddedDate(): ?\DateTimeInterface
    {
        return $this->added_date;
    }

    public function setAddedDate(\DateTimeInterface $added_date): static
    {
        $this->added_date = $added_date;

        return $this;
    }

    public function getBookStatus(): ?string
    {
        return $this->book_status;
    }

    public function setBookStatus(?string $book_status): static
    {
        $this->book_status = $book_status;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(?\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }
}
