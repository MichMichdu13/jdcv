<?php

namespace App\Entity;

use App\Repository\ImgLogementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ImgLogementRepository::class)]
class ImgLogement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getLogement"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'imgLogements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Logement $logement = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getLogement"])]
    private ?string $filename = null;

    #[ORM\Column]
    #[Groups(["getLogement"])]
    private ?bool $imgMain = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogement(): ?Logement
    {
        return $this->logement;
    }

    public function setLogement(?Logement $logement): self
    {
        $this->logement = $logement;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function isImgMain(): ?bool
    {
        return $this->imgMain;
    }

    public function setImgMain(bool $imgMain): self
    {
        $this->imgMain = $imgMain;

        return $this;
    }
}
