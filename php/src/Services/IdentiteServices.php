<?php

namespace App\Services;

use App\Repository\IdentiteRepository;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class IdentiteServices extends AbstractController
{
    private $identiteRepository;

    public function __construct(IdentiteRepository $identiteRepository)
    {
        $this->identiteRepository = $identiteRepository;
    }

    public function user()
    {
        $response = $this->statusCode(Response::HTTP_OK, $this->identiteRepository->findOneBy(['user' => $this->getUser()->getId()]));
        return $this->json($response, $response["status"], [], ["groups" => "read:identite:item"]);
    }

    public function index(): Response
    {
        // Cette action ne nécessite pas d'autorisation spécifique
        $response = $this->statusCode(Response::HTTP_OK, $this->identiteRepository->findAll());
        return $this->json($response, $response["status"], [], ["groups" => "read:identite:list"]);
    }
}
