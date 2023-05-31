<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventToLogement::class)]
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
     * @return Collection<int, EventToLogement>
     */
    public function getLogement(): Collection
    {
        return $this->logement;
    }

    public function addLogement(EventToLogement $logement): self
    {
        if (!$this->logement->contains($logement)) {
            $this->logement->add($logement);
            $logement->setEvent($this);
        }

        return $this;
    }

    public function removeLogement(EventToLogement $logement): self
    {
        if ($this->logement->removeElement($logement)) {
            // set the owning side to null (unless already changed)
            if ($logement->getEvent() === $this) {
                $logement->setEvent(null);
            }
        }

        return $this;
    }
}
