<?php

namespace App\Entity;

use App\Repository\TagsToLogementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagsToLogementRepository::class)]
class TagsToLogement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'logement')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tags $tag = null;

    #[ORM\ManyToOne(inversedBy: 'tags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Logement $logement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTag(): ?Tags
    {
        return $this->tag;
    }

    public function setTag(?Tags $tag): self
    {
        $this->tag = $tag;

        return $this;
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
}
