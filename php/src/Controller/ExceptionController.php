<?php

namespace App\Controller;


use App\Controller\AbstractController;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ExceptionController extends AbstractController
{
    public function catchException(\Throwable $exception)
    {

        if ($exception instanceof NotFoundHttpException || $exception instanceof MethodNotAllowedHttpException) {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        } elseif ($exception instanceof UniqueConstraintViolationException) {
            $response = $this->statusCode(Response::HTTP_BAD_REQUEST, [], "Les données que vous souhaitez persister existe déjà dans la base de données");
        } elseif ($exception instanceof AccessDeniedException || $exception instanceof AccessDeniedHttpException) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN);
        } elseif ($exception instanceof \TypeError) {
            $response = $this->statusCode(Response::HTTP_BAD_REQUEST);
        } else {
            $response = [
                "success" => false,
                "status" => Response::HTTP_INTERNAL_SERVER_ERROR,
                "message" => $exception->getMessage()
            ];
        }

        return $this->json($response, $response["status"]);
    }
}
