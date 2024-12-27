<?php

namespace App\Controller;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\Competence\CompetencesServices;

class CompetencesClientController extends AbstractController
{
    #[Route('/competences_client', name: 'competences_clients_index', methods: ['GET'])]
    public function index(CompetencesServices $competencesServices): Response
    {
        // Ici, nous vérifions si l'utilisateur actuel est autorisé à modifier cette identité.
        if (!$user = $this->getUser()) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN);
            return $this->json($response, $response['status']);
        }
        return $competencesServices->find_all_competences_except_user($user->getId());
    }


    #[Route('/competences_client/{id}', name: 'competences_client_index', methods: ['GET'])]
    public function listCompetencesByUserId(int $id, CompetencesServices $competencesServices): Response
    {
        return $competencesServices->find_all_competences_by_user($id);
    }
}
