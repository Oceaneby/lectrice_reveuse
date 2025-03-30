<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $isbn = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $publication_date = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $cover_image = null;

    // #[ORM\ManyToOne(inversedBy: 'books')]
    // #[ORM\JoinColumn(nullable: false)]
    // private ?Author $author = null;
    #[ORM\ManyToMany(targetEntity: Author::class, mappedBy: 'books')]
    private Collection $authors;

    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'books')]
    #[ORM\JoinTable(name: "book_genres")]
    private Collection $genres;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Publisher $publisher = null;

    /**
     * @var Collection<int, Library>
     */
    #[ORM\ManyToMany(targetEntity: Library::class, mappedBy: 'books', orphanRemoval: true)]
    private Collection $libraries;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'book', orphanRemoval: true)]
    private Collection $reviews;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\ManyToMany(targetEntity: Bookshelf::class, mappedBy:'book')]
    private Collection $bookshelves;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $amazonUrl= null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $fnacUrl = null;

    public function __construct()
    {
        $this->libraries = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->bookshelves = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->authors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publication_date;
    }

    public function setPublicationDate(?\DateTimeInterface $publication_date): static
    {
        $this->publication_date = $publication_date;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->cover_image;
    }

    public function setCoverImage($cover_image): self
    {
        if ($cover_image instanceof UploadedFile) {
            // Si c'est un objet UploadedFile, on le déplace et on conserve son nom dans la base de données
            $this->cover_image = $cover_image->getClientOriginalName();
        } else {
            // Sinon, c'est probablement déjà une chaîne de caractères (nom du fichier)
            $this->cover_image = (string) $cover_image;
        }

        return $this;
    }

    public function getAuthors(): Collection
    {
        return $this->authors;
    }
    public function addAuthor(Author $author): static
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): static
    {
        $this->authors->removeElement($author);

        return $this;
    }

    // public function setAuthor(?Author $author): static
    // {
    //     $this->author = $author;

    //     return $this;
    // }

    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    public function getPublisher(): ?Publisher
    {
        return $this->publisher;
    }

    public function setPublisher(?Publisher $publisher): static
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * @return Collection<int, Library>
     */
    public function getLibraries(): Collection
    {
        return $this->libraries;
    }

    public function addLibrary(Library $library): static
    {
        if (!$this->libraries->contains($library)) {
            $this->libraries[] = $library;
           
        }

        return $this;
    }

    public function removeLibrary(Library $library): static
    {
        if ($this->libraries->removeElement($library)) {
            // set the owning side to null (unless already changed)
           
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setBook($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getBook() === $this) {
                $review->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Bookshelf>
     */
    public function getBookshelves(): Collection
    {
        return $this->bookshelves;
    }

    public function addBookshelf(Bookshelf $bookshelf): static
    {
        if (!$this->bookshelves->contains($bookshelf)) {
            $this->bookshelves->add($bookshelf);
            $bookshelf->addBook($this);
        }

        return $this;
    }

    public function removeBookshelf(Bookshelf $bookshelf): static
    {
        if ($this->bookshelves->removeElement($bookshelf)) {
            $bookshelf->removeBook($this);
        }

        return $this;
    }
    public function getAmazonUrl(): ?string
    {
        return $this->amazonUrl;
    }

    public function setAmazonUrl(?string $amazonUrl): self
    {
        $this->amazonUrl = $amazonUrl;
        return $this;
    }

    public function getFnacUrl(): ?string
    {
        return $this->fnacUrl;
    }

    public function setFnacUrl(?string $fnacUrl): self
    {
        $this->fnacUrl = $fnacUrl;
        return $this;
    }
}
