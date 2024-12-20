<?php

namespace App\EventListener;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;

class JWTInvalidListener extends AbstractController
{

    /**
     * @param JWTInvalidEvent $event
     */
    public function onJWTInvalid(JWTInvalidEvent $event)
    {
        $response = new JsonResponse($this->statusCode(Response::HTTP_UNAUTHORIZED, [], "Votre token n'est plus valide, veuillez vous reconnectez pour en obtenir un nouveau."), 401);
        $event->setResponse($response);
    }
}
