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

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
}
