<?php

// AdresseDto.php
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class AdresseDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public string $rue;

    #[Assert\Positive]
    #[Assert\Length(min: 1, max: 3)]
    public ?int $appartement;

    #[Assert\Positive]
    #[Assert\Length(min: 4, max: 6)]
    public ?int $codePostal;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 4)]
    public string $ville;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 4)]
    public string $pays;

    public function __construct(
        string $rue,
        ?int $appartement,
        int $codePostal,
        string $ville,
        string $pays
    ) {
        $this->rue = $rue;
        $this->appartement = $appartement;
        $this->codePostal = $codePostal;
        $this->ville = $ville;
        $this->pays = $pays;
    }
}
