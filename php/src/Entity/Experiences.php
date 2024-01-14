<?php

namespace App\Entity;

use App\Repository\ExperiencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExperiencesRepository::class)]
class Experiences
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $debutAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $finAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $enCour = null;

    #[ORM\OneToMany(mappedBy: 'experience', targetEntity: realisations::class)]
    private Collection $realisations;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modifyAt = null;

    #[ORM\ManyToOne(inversedBy: 'experience')]
    private ?Entreprises $yes = null;

    #[ORM\ManyToOne(inversedBy: 'experiences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
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

    public function getDebutAt(): ?\DateTimeImmutable
    {
        return $this->debutAt;
    }

    public function setDebutAt(\DateTimeImmutable $debutAt): self
    {
        $this->debutAt = $debutAt;

        return $this;
    }

    public function getFinAt(): ?\DateTimeImmutable
    {
        return $this->finAt;
    }

    public function setFinAt(?\DateTimeImmutable $finAt): self
    {
        $this->finAt = $finAt;

        return $this;
    }

    public function isEnCour(): ?bool
    {
        return $this->enCour;
    }

    public function setEnCour(?bool $enCour): self
    {
        $this->enCour = $enCour;

        return $this;
    }

    /**
     * @return Collection<int, realisations>
     */
    public function getRealisations(): Collection
    {
        return $this->realisations;
    }

    public function addRealisations(realisations $realisation): self
    {
        if (!$this->realisations->contains($realisation)) {
            $this->realisations->add($realisation);
            $realisation->setExperience($this);
        }

        return $this;
    }

    public function removeRealisations(realisations $realisation): self
    {
        if ($this->realisations->removeElement($realisation)) {
            // set the owning side to null (unless already changed)
            if ($realisation->getExperience() === $this) {
                $realisation->setExperience(null);
            }
        }

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): self
    {
        $this->createAt = $createAt;

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

    public function getYes(): ?Entreprises
    {
        return $this->yes;
    }

    public function setYes(?Entreprises $yes): static
    {
        $this->yes = $yes;

        return $this;
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
}
