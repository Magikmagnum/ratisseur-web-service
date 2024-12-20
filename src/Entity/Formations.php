<?php

namespace App\Entity;

use App\Repository\FormationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FormationsRepository::class)]
class Formations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:formation:list', 'read:formation:item'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'formation')]
    #[Groups(['read:formation:list', 'read:formation:item'])]
    private ?FormationsListe $label = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['read:formation:item'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:formation:item'])]
    private ?\DateTimeImmutable $debutAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:formation:item'])]
    private ?\DateTimeImmutable $finAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:formation:item'])]
    private ?bool $enCour = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modifyAt = null;

    #[ORM\ManyToOne(inversedBy: 'formation')]
    #[Groups(['read:formation:list', 'read:formation:item'])]
    private ?Entreprises $entreprise = null;

    #[ORM\ManyToOne(inversedBy: 'formations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    // Ajout du constructeur
    public function __construct()
    {
        // Convertir la chaîne de date en objet DateTimeImmutable
        $this->setCreatedAt(new \DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?FormationsListe
    {
        return $this->label;
    }

    public function setLabel(?FormationsListe $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDebutAt(): ?\DateTimeImmutable
    {
        return $this->debutAt;
    }

    public function setDebutAt(?\DateTimeImmutable $debutAt): static
    {
        $this->debutAt = $debutAt;

        return $this;
    }

    public function getFinAt(): ?\DateTimeImmutable
    {
        return $this->finAt;
    }

    public function setFinAt(?\DateTimeImmutable $finAt): static
    {
        $this->finAt = $finAt;

        return $this;
    }

    public function isEnCour(): ?bool
    {
        return $this->enCour;
    }

    public function setEnCour(?bool $enCour): static
    {
        $this->enCour = $enCour;

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

    public function getEntreprise(): ?Entreprises
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprises $entreprise): static
    {
        $this->entreprise = $entreprise;

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
