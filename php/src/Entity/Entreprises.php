<?php

namespace App\Entity;

use App\Repository\EntreprisesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EntreprisesRepository::class)]
class Entreprises
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:experience:item', 'read:formation:list', 'read:formation:item'])]
    private ?string $label = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:experience:item', 'read:formation:list', 'read:formation:item'])]
    private ?bool $etablissement = null;

    #[ORM\Column]
    #[Groups(['read:experience:item'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modifyAt = null;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Experiences::class)]
    private Collection $experience;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Formations::class)]
    private Collection $formation;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
        $this->experience = new ArrayCollection();
        $this->formation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function isEtablissement(): ?bool
    {
        return $this->etablissement;
    }

    public function setEtablissement(?bool $etablissement): static
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifyAt(): ?\DateTimeImmutable
    {
        return $this->modifyAt;
    }

    public function setModifyAt(?\DateTimeImmutable $modifyAt): static
    {
        $this->modifyAt = $modifyAt;

        return $this;
    }

    /**
     * @return Collection<int, Experiences>
     */
    public function getExperience(): Collection
    {
        return $this->experience;
    }

    public function addExperience(Experiences $experience): static
    {
        if (!$this->experience->contains($experience)) {
            $this->experience->add($experience);
            $experience->setEntreprise($this);
        }

        return $this;
    }

    public function removeExperience(Experiences $experience): static
    {
        if ($this->experience->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getEntreprise() === $this) {
                $experience->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Formations>
     */
    public function getFormation(): Collection
    {
        return $this->formation;
    }

    public function addFormation(Formations $formation): static
    {
        if (!$this->formation->contains($formation)) {
            $this->formation->add($formation);
            $formation->setEntreprise($this);
        }

        return $this;
    }

    public function removeFormation(Formations $formation): static
    {
        if ($this->formation->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getEntreprise() === $this) {
                $formation->setEntreprise(null);
            }
        }

        return $this;
    }
}
