<?php

namespace App\Controller;

use App\Entity\Competences;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Competence\CompetencesServices;

#[Route('competences')]
class CompetencesController extends AbstractController
{
    #[Route('', name: 'competences_index', methods: ['GET'])]
    public function index(CompetencesServices $competencesServices): Response
    {
        return $competencesServices->listerLesCompetences();
    }

    #[Route('/user', name: 'competences_user_index', methods: ['GET'])]
    public function index_user(CompetencesServices $competencesServices): Response
    {
        return $competencesServices->listerLesCompetencesUtilisateur();
    }

    #[Route('/{id}', name: 'competences_show', methods: ['GET'])]
    public function show($id, CompetencesServices $competencesServices): Response
    {
        return $competencesServices->detailCompetences($id);
    }

    #[Route('', name: 'competences_new', methods: ['POST'])]
    public function add(Request $request, CompetencesServices $competencesServices): Response
    {
        return $competencesServices->creerUneCompetence($request);
    }

    #[Route('/{id}', name: 'competences_edit', methods: ['POST'])]
    public function edit($id, Request $request, CompetencesServices $competencesServices): Response
    {
        return $competencesServices->modifierUneCompetence($id, $request);
    }

    #[Route('/{id}', name: 'competences_delete', methods: ['DELETE'])]
    public function delete($id, CompetencesServices $competencesServices): Response
    {
        return $competencesServices->supprimerUneCompetence($id);
    }
}
