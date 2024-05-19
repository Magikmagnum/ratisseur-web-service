<?php

// AdresseDto.php
use Symfony\Component\Validator\Constraints as Assert;

class AdresseDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public string $rue;

    #[Assert\Positive]
    public int $appartement;

    #[Assert\Positive]
    public int $postalCode;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public string $city;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public string $country;

    public function __construct(
        string $rue,
        int $appartement,
        int $postalCode,
        string $city,
        string $country
    ) {
        $this->rue = $rue;
        $this->appartement = $appartement;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->country = $country;
    }
}
