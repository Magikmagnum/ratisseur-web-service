<?php

namespace App\Services\Competence;

use App\Entity\CompetencesListe;
use App\Controller\AbstractController;
use App\Repository\CompetencesListeRepository;


class CompetencesListeServices extends AbstractController
{
    private CompetencesListeRepository $competencesListeRepository;

    public function __construct(CompetencesListeRepository $competencesListeRepository)
    {
        $this->competencesListeRepository = $competencesListeRepository;
    }


    public function getCompetenceLabel(string $label): CompetencesListe
    {
        if (!$competenceListe = $this->competencesListeRepository->findOneBy(['label' => $label])) {
            $competenceListe = new CompetencesListe();
            $competenceListe->setLabel($label);

            if ($validationErrors = $this->validateEntity($competenceListe)) {
                return $this->json($validationErrors, $validationErrors['status']);
            }
            $this->saveEntity($competenceListe, true);
        }
        return $competenceListe;
    }
}
