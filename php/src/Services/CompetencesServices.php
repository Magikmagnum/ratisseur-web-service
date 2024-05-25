<?php

namespace App\Services;

use App\Controller\AbstractController;
use App\Repository\CompetencesRepository;
use Symfony\Component\HttpFoundation\Response;

class CompetencesServices extends AbstractController
{
    private $competencesRepository;
    private $userServices;

    public function __construct(
        CompetencesRepository $competencesRepository,
        UserServices $userServices
    ) {
        $this->competencesRepository = $competencesRepository;
        $this->userServices = $userServices;
    }

    public function find_all_competences_by_user(Int $id): Response
    {
        // Récupérer l'utilisateur par son ID
        // Retourner la réponse JSON
        $response = $this->statusCode(Response::HTTP_OK, $this->competencesRepository->findBy(['user' => $this->userServices->get_user_by_id($id)]));
        return $this->json($response, $response['status'], [], ['groups' => 'read:competence:list']);
    }

    public function find_all_competences_except_user(Int $id): Response
    {
        // Récupérer l'utilisateur par son ID
        // Retourner la réponse JSON
        $response = $this->statusCode(Response::HTTP_OK, $this->competencesRepository->findAllExcepteUser($this->userServices->get_user_by_id($id)));
        return $this->json($response, $response['status'], [], ['groups' => 'read:competence:list']);
    }
}
