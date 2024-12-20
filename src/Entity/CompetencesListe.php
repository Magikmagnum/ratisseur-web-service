<?php

namespace App\Entity;

use App\Repository\CompetencesListeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CompetencesListeRepository::class)]
class CompetencesListe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ 'label' ne doit pas être vide.")]
    #[Groups(['read:competence:list', 'read:competence:item'])]
    private ?string $label = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modifyAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $valide = null;

    #[ORM\OneToMany(mappedBy: 'label', targetEntity: Competences::class)]
    private Collection $competences;


    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->setCreatedAt(new \DateTimeImmutable());
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

    public function isValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(?bool $valide): static
    {
        $this->valide = $valide;

        return $this;
    }

    /**
     * @return Collection<int, Competences>
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competences $competence): static
    {
        if (!$this->competences->contains($competence)) {
            $this->competences->add($competence);
            $competence->setLabel($this);
        }

        return $this;
    }

    public function removeCompetence(Competences $competence): static
    {
        if ($this->competences->removeElement($competence)) {
            // set the owning side to null (unless already changed)
            if ($competence->getLabel() === $this) {
                $competence->setLabel(null);
            }
        }

        return $this;
    }
}
