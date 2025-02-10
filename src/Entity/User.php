<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: "Nom d'utilisateur indisponible.")]
#[UniqueEntity(fields: ['email'], message: "Email indisponible.")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $registration_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    
    // #[Assert\Date(message: 'Veuillez entrer une date valide.')]
    #[Assert\LessThan("today", message:'La date de naissance doit être dans le passé.')]
    #[Assert\GreaterThan("1900-01-01", message: "La date de naissance doit être après le 1er janvier 1900.")]
    private ?\DateTimeInterface $birth_date = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var Collection<int, Bookshelf>
     */
    #[ORM\OneToMany(targetEntity: Bookshelf::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $bookshelves;

    /**
     * @var Collection<int, ProfilPicture>
     */
    #[ORM\OneToMany(targetEntity: ProfilPicture::class, mappedBy: 'user')]
    private Collection $profilPictures;

    /**
     * @var Collection<int, Library>
     */
    #[ORM\OneToMany(targetEntity: Library::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $libraries;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $reviews;

    public function __construct()
    {
        $this->bookshelves = new ArrayCollection();
        $this->profilPictures = new ArrayCollection();
        $this->libraries = new ArrayCollection();
        $this->reviews = new ArrayCollection();
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    public function getUserIdentifier(): string
    {
        return $this->email; // Assuming "email" is the unique identifier
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registration_date;
    }

    public function setRegistrationDate(\DateTimeInterface $registration_date): static
    {
        $this->registration_date = $registration_date;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthDate(\DateTimeInterface $birth_date): static
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getRoles(): array
    {
        // return $this->roles;
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

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
            $bookshelf->setUser($this);
        }

        return $this;
    }

    public function removeBookshelf(Bookshelf $bookshelf): static
    {
        if ($this->bookshelves->removeElement($bookshelf)) {
            // set the owning side to null (unless already changed)
            if ($bookshelf->getUser() === $this) {
                $bookshelf->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProfilPicture>
     */
    public function getProfilPictures(): Collection
    {
        return $this->profilPictures;
    }

    public function addProfilPicture(ProfilPicture $profilPicture): static
    {
        if (!$this->profilPictures->contains($profilPicture)) {
            $this->profilPictures->add($profilPicture);
            $profilPicture->setUser($this);
        }

        return $this;
    }

    public function removeProfilPicture(ProfilPicture $profilPicture): static
    {
        if ($this->profilPictures->removeElement($profilPicture)) {
            // set the owning side to null (unless already changed)
            if ($profilPicture->getUser() === $this) {
                $profilPicture->setUser(null);
            }
        }

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
            $this->libraries->add($library);
            $library->setUser($this);
        }

        return $this;
    }

    public function removeLibrary(Library $library): static
    {
        if ($this->libraries->removeElement($library)) {
            // set the owning side to null (unless already changed)
            if ($library->getUser() === $this) {
                $library->setUser(null);
            }
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
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }
}
