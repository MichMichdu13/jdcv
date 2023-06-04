<?php

namespace App\Entity;

use App\Repository\LogementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LogementRepository::class)]
class Logement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getLogement"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'logements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getLogement"])]
    private ?User $User = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getLogement"])]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["getLogement"])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(["getLogement"])]
    private ?float $prixNuit = null;


    #[ORM\Column]
    #[Groups(["getLogement"])]
    private ?int $nbPersonne = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getLogement"])]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getLogement"])]
    private ?string $cp = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getLogement"])]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getLogement"])]
    private ?string $gps = null;

    #[ORM\OneToMany(mappedBy: 'logement', targetEntity: TagsToLogement::class)]
    private Collection $tags;

    #[ORM\OneToMany(mappedBy: 'logement', targetEntity: ImgLogement::class)]
    #[Groups(["getLogement"])]
    private Collection $imgLogements;

    #[ORM\OneToMany(mappedBy: 'logement', targetEntity: EventToLogement::class)]
    private Collection $event;

    #[ORM\OneToMany(mappedBy: 'logement', targetEntity: StyleToLogement::class)]
    private Collection $style;

    #[ORM\OneToMany(mappedBy: 'logement', targetEntity: Reservation::class)]
    private Collection $reservations;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->imgLogements = new ArrayCollection();
        $this->event = new ArrayCollection();
        $this->style = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrixNuit(): ?float
    {
        return $this->prixNuit;
    }

    public function setPrixNuit(float $prixNuit): self
    {
        $this->prixNuit = $prixNuit;

        return $this;
    }


    public function getNbPersonne(): ?int
    {
        return $this->nbPersonne;
    }

    public function setNbPersonne(int $nbPersonne): self
    {
        $this->nbPersonne = $nbPersonne;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getGps(): ?string
    {
        return $this->gps;
    }

    public function setGps(string $gps): self
    {
        $this->gps = $gps;

        return $this;
    }

    /**
     * @return Collection<int, TagsToLogement>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(TagsToLogement $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->setLogement($this);
        }

        return $this;
    }

    public function removeTag(TagsToLogement $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            // set the owning side to null (unless already changed)
            if ($tag->getLogement() === $this) {
                $tag->setLogement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ImgLogement>
     */
    public function getImgLogements(): Collection
    {
        return $this->imgLogements;
    }

    public function addImgLogement(ImgLogement $imgLogement): self
    {
        if (!$this->imgLogements->contains($imgLogement)) {
            $this->imgLogements->add($imgLogement);
            $imgLogement->setLogement($this);
        }

        return $this;
    }

    public function removeImgLogement(ImgLogement $imgLogement): self
    {
        if ($this->imgLogements->removeElement($imgLogement)) {
            // set the owning side to null (unless already changed)
            if ($imgLogement->getLogement() === $this) {
                $imgLogement->setLogement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EventToLogement>
     */
    public function getEvent(): Collection
    {
        return $this->event;
    }

    public function addEvent(EventToLogement $event): self
    {
        if (!$this->event->contains($event)) {
            $this->event->add($event);
            $event->setLogement($this);
        }

        return $this;
    }

    public function removeEvent(EventToLogement $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getLogement() === $this) {
                $event->setLogement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StyleToLogement>
     */
    public function getStyle(): Collection
    {
        return $this->style;
    }

    public function addStyle(StyleToLogement $style): self
    {
        if (!$this->style->contains($style)) {
            $this->style->add($style);
            $style->setLogement($this);
        }

        return $this;
    }

    public function removeStyle(StyleToLogement $style): self
    {
        if ($this->style->removeElement($style)) {
            // set the owning side to null (unless already changed)
            if ($style->getLogement() === $this) {
                $style->setLogement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setLogement($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getLogement() === $this) {
                $reservation->setLogement(null);
            }
        }

        return $this;
    }
}
