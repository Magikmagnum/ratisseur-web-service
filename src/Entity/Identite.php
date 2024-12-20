<?php

namespace App\Entity;

use App\Repository\IdentiteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IdentiteRepository::class)]
class Identite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:identite:list', 'read:identite:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ 'nom' ne doit pas être vide.")]
    #[Groups(['read:identite:list', 'read:identite:item', 'read:competence:list', 'read:competence:item'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Choice(
        choices: [true, false],
        message: "La valeur du champ 'sexe' doit être soit 'true' pour masculin, soit 'false' pour féminin."
    )]
    #[Groups(['read:identite:list', 'read:identite:item', 'read:competence:list', 'read:competence:item'])]
    private ?bool $sexe = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:identite:list', 'read:identite:item', 'read:competence:list', 'read:competence:item'])]
    private ?\DateTimeImmutable $naissanceAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modifyAt = null;

    #[ORM\OneToOne(inversedBy: 'identite')]
    // #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function isSexe(): ?bool
    {
        return $this->sexe;
    }

    public function setSexe(?bool $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getNaissanceAt(): ?\DateTimeImmutable
    {
        return $this->naissanceAt;
    }

    public function setNaissanceAt(?\DateTimeImmutable $naissanceAt): self
    {
        $this->naissanceAt = $naissanceAt;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
