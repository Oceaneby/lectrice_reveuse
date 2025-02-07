<?php

namespace App\Entity;

use App\Repository\DefaultAvatarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DefaultAvatarRepository::class)]
class DefaultAvatar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $image_url = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, ProfilPicture>
     */
    #[ORM\OneToMany(targetEntity: ProfilPicture::class, mappedBy: 'default_avatar')]
    private Collection $profilPictures;

    public function __construct()
    {
        $this->profilPictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): static
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

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
            $profilPicture->setDefaultAvatar($this);
        }

        return $this;
    }

    public function removeProfilPicture(ProfilPicture $profilPicture): static
    {
        if ($this->profilPictures->removeElement($profilPicture)) {
            // set the owning side to null (unless already changed)
            if ($profilPicture->getDefaultAvatar() === $this) {
                $profilPicture->setDefaultAvatar(null);
            }
        }

        return $this;
    }
}
