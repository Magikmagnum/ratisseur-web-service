<?php

namespace App\EventListener;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;


class JWTExpiredListener extends AbstractController
{
    /**
     * @param JWTExpiredEvent $event
     */
    public function onJWTExpired(JWTExpiredEvent $event)
    {
        $response = new JsonResponse($this->statusCode(Response::HTTP_UNAUTHORIZED, [], "Votre session a expiré, veuillez la renouvelé."), 401);
        $event->setResponse($response);
    }
}
