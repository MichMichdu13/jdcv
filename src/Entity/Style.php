<?php

namespace App\Entity;

use App\Repository\StyleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StyleRepository::class)]
class Style
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'style', targetEntity: StyleToLogement::class)]
    private Collection $logement;

    public function __construct()
    {
        $this->logement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, StyleToLogement>
     */
    public function getLogement(): Collection
    {
        return $this->logement;
    }

    public function addLogement(StyleToLogement $logement): self
    {
        if (!$this->logement->contains($logement)) {
            $this->logement->add($logement);
            $logement->setStyle($this);
        }

        return $this;
    }

    public function removeLogement(StyleToLogement $logement): self
    {
        if ($this->logement->removeElement($logement)) {
            // set the owning side to null (unless already changed)
            if ($logement->getStyle() === $this) {
                $logement->setStyle(null);
            }
        }

        return $this;
    }
}
