<?php

namespace App\Entity;

use App\Repository\CompetencesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CompetencesRepository::class)]
class Competences
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:competence:list', 'read:competence:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ 'label' ne doit pas être vide.")]
    #[Groups(['read:competence:list', 'read:competence:item'])]
    private ?string $label = null;

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

    // Ajout du constructeur
    public function __construct()
    {
        // Convertir la chaîne de date en objet DateTimeImmutable
        $createdAt = new \DateTimeImmutable();
        $this->setCreatedAt($createdAt);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

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
}
