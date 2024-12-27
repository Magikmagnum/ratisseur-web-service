<?php

namespace App\Services\Competence;

use App\Entity\Competences;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

interface CompetenceInterface
{
    public function detailCompetences(int $id): JsonResponse;
    public function listerLesCompetences(): JsonResponse;
    public function listerLesCompetencesUtilisateur(): JsonResponse;
    public function creerUneCompetence(Request $request): JsonResponse;
    public function modifierUneCompetence(int $id, Request $request): JsonResponse;
    public function supprimerUneCompetence(int $id): JsonResponse;
}
