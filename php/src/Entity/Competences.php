<?php

namespace App\Entity;

use App\Repository\CompetencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CompetencesRepository::class)]
class Competences
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:competence:list', 'read:competence:item'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'competences')]
    #[Groups(['read:competence:list', 'read:competence:item'])]
    private ?CompetencesListe $label = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['read:competence:list', 'read:competence:item'])]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modifyAt = null;

    #[ORM\ManyToOne(inversedBy: 'competences')]
    #[Groups(['read:competence:list', 'read:competence:item'])]
    private ?user $user = null;

    #[ORM\OneToMany(mappedBy: 'competence', targetEntity: Realisations::class, orphanRemoval: true)]
    private Collection $realisations;

    // Ajout du constructeur
    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
        $this->realisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifyAt(): ?\DateTimeImmutable
    {
        return $this->modifyAt;
    }

    public function setModifyAt(?\DateTimeImmutable $modifyAt): self
    {
        $this->modifyAt = $modifyAt;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Realisations>
     */
    public function getRealisations(): Collection
    {
        return $this->realisations;
    }

    public function addRealisation(Realisations $realisation): self
    {
        if (!$this->realisations->contains($realisation)) {
            $this->realisations->add($realisation);
            $realisation->setCompetence($this);
        }

        return $this;
    }

    public function removeRealisation(Realisations $realisation): self
    {
        if ($this->realisations->removeElement($realisation)) {
            // set the owning side to null (unless already changed)
            if ($realisation->getCompetence() === $this) {
                $realisation->setCompetence(null);
            }
        }

        return $this;
    }

    public function getLabel(): ?CompetencesListe
    {
        return $this->label;
    }

    public function setLabel(?CompetencesListe $label): static
    {
        $this->label = $label;

        return $this;
    }
}
