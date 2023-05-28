<?php

namespace App\Entity;

use App\Repository\TagsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TagsRepository::class)]
class Tags
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["tag"])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(["tag"])]
    private ?string $tag = null;

    #[ORM\OneToMany(mappedBy: 'tag', targetEntity: TagsToLogement::class)]
    private Collection $logement;

    public function __construct()
    {
        $this->logement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return Collection<int, TagsToLogement>
     */
    public function getLogement(): Collection
    {
        return $this->logement;
    }

    public function addLogement(TagsToLogement $logement): self
    {
        if (!$this->logement->contains($logement)) {
            $this->logement->add($logement);
            $logement->setTag($this);
        }

        return $this;
    }

    public function removeLogement(TagsToLogement $logement): self
    {
        if ($this->logement->removeElement($logement)) {
            // set the owning side to null (unless already changed)
            if ($logement->getTag() === $this) {
                $logement->setTag(null);
            }
        }

        return $this;
    }
}
