<?php

namespace App\Entity;

use App\Repository\FormationsListeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FormationsListeRepository::class)]
class FormationsListe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:formation:list', 'read:formation:item'])]
    private ?string $label = null;

    #[ORM\Column(nullable: true)]
    private ?bool $valide = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modifyAt = null;

    #[ORM\OneToMany(mappedBy: 'label', targetEntity: Formations::class)]
    private Collection $formations;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
        $this->formations = new ArrayCollection();
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

    public function isValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(?bool $valide): static
    {
        $this->valide = $valide;

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
     * @return Collection<int, Formations>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormations(Formations $formations): static
    {
        if (!$this->formations->contains($formations)) {
            $this->formations->add($formations);
            $formations->setLabel($this);
        }

        return $this;
    }

    public function removeFormations(Formations $formations): static
    {
        if ($this->formations->removeElement($formations)) {
            // set the owning side to null (unless already changed)
            if ($formations->getLabel() === $this) {
                $formations->setLabel(null);
            }
        }

        return $this;
    }
}
