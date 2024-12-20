<?php

namespace App\EventListener;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;



class AuthenticationFailureListener extends AbstractController
{
    /**
     * @param AuthenticationFailureEvent  $event
     */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent  $event)
    {
        $response = new JsonResponse($this->statusCode(Response::HTTP_UNAUTHORIZED, [], "Impossible de vous authentifier, mot de passe ou email invalide"), 401);
        $event->setResponse($response);
    }
}
