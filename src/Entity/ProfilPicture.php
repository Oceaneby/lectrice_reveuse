<?php

namespace App\Entity;

use App\Repository\ProfilPictureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfilPictureRepository::class)]
class ProfilPicture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'profilPictures')]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $image_url = null;

    #[ORM\Column(nullable: true)]
    private ?int $file_size = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $file_format = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $upload_date = null;

    #[ORM\ManyToOne(inversedBy: 'profilPictures')]
    private ?DefaultAvatar $default_avatar = null;

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

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): static
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function getFileSize(): ?int
    {
        return $this->file_size;
    }

    public function setFileSize(?int $file_size): static
    {
        $this->file_size = $file_size;

        return $this;
    }

    public function getFileFormat(): ?string
    {
        return $this->file_format;
    }

    public function setFileFormat(?string $file_format): static
    {
        $this->file_format = $file_format;

        return $this;
    }

    public function getUploadDate(): ?\DateTimeInterface
    {
        return $this->upload_date;
    }

    public function setUploadDate(?\DateTimeInterface $upload_date): static
    {
        $this->upload_date = $upload_date;

        return $this;
    }

    public function getDefaultAvatar(): ?DefaultAvatar
    {
        return $this->default_avatar;
    }

    public function setDefaultAvatar(?DefaultAvatar $default_avatar): static
    {
        $this->default_avatar = $default_avatar;

        return $this;
    }
}
