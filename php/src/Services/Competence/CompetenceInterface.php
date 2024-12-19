<?php

namespace App\Services\Competence;

use Symfony\Component\HttpFoundation\Request;

interface CompetenceInterface
{
    public function creerUneCompetence(Request $request);
    public function modifierUneCompetence(Request $request);
    public function supprimerUneCompetence(Request $request);
    public function listerLesCompetences(Request $request);
    public function listerLesCompetencesParUtilisateur(Request $request);
    public function listerLesCompetencesSolicite(Request $request);
}
