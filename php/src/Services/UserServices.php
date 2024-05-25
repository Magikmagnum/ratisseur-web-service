<?php

namespace App\Services;

use App\Entity\User;
use App\Controller\AbstractController;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class UserServices extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function get_user_by_id(Int $id): User | Response
    {
        // Récupérer l'utilisateur par son ID
        $user = $this->userRepository->find($id);
        if (!$user) {
            // Retourner une réponse 404 si l'utilisateur n'est pas trouvé
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
            return $this->json($response, $response['status']);
        }
        return $user;
    }
}
