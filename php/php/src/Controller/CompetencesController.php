<?php

namespace App\Controller;

use App\Entity\Competences;
use App\Entity\CompetencesListe;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CompetencesListeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Competence\CompetencesServices;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('competences')]
class CompetencesController extends AbstractController
{
    #[Route('', name: 'competences_index', methods: ['GET'])]
    public function index(CompetencesServices $competencesServices): Response
    {
        // Ici, nous vérifions si l'utilisateur actuel est autorisé à modifier cette identité.
        if (!$user = $this->getUser()) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN);
            return $this->json($response, $response['status']);
        }
        return $competencesServices->find_all_competences_by_user($user->getId());
    }

    #[Route('/{id}', name: 'competences_show', methods: ['GET'])]
    public function show(Competences $competence): Response
    {
        /// TODO: ajouter un voter
        $response = $this->statusCode(Response::HTTP_OK, $competence);
        return $this->json($response, $response["status"], [], ["groups" => "read:competence:item"]);
    }

    #[Route('', name: 'competences_new', methods: ['POST'])]
    public function add(Request $request, CompetencesServices $competencesServices): Response
    {
        return $competencesServices->creerUneCompetence($request);
    }

    #[Route('/{id}', name: 'competences_id', methods: ['POST'])]
    public function edit(Competences $competense, Request $request, CompetencesServices $competencesServices): Response
    {
        return $competencesServices->modifierUneCompetence($competense, $request);
    }






















    #[Route('/{id}', name: 'competences_delete', methods: ['DELETE'])]
    public function delete(Competences $competence): Response
    {
        // Ici, nous vérifions si l'utilisateur actuel est autorisé à modifier cette identité.
        if (!$this->isGranted('DELETE', $competence)) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN, 'Vous n\'avez pas la permission de modifier cette identité.');
            return $this->json($response, $response['status']);
        }

        $entityManager = $this->getManager();
        $entityManager->remove($competence);
        $entityManager->flush();

        $response = $this->statusCode(Response::HTTP_OK);
        return $this->json($response, $response['status']);
    }
}
