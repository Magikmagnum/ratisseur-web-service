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
use Symfony\Component\Serializer\Exception\CircularReferenceException;

class ExceptionController extends AbstractController
{
    public function catchException(\Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException || $exception instanceof MethodNotAllowedHttpException) {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        } elseif ($exception instanceof UniqueConstraintViolationException) {
            $response = $this->statusCode(Response::HTTP_BAD_REQUEST, [], "Les données que vous souhaitez persister existent déjà dans la base de données");
        } elseif ($exception instanceof AccessDeniedException || $exception instanceof AccessDeniedHttpException) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN);
        } elseif ($exception instanceof \ErrorException && strpos($exception->getMessage(), 'Trying to access array offset on value of type null') !== false) {
            $response = $this->statusCode(Response::HTTP_BAD_REQUEST);
        } elseif ($exception instanceof \TypeError) {
            $response = $this->statusCode(Response::HTTP_BAD_REQUEST);
        } elseif ($exception instanceof \Exception && strpos($exception->getMessage(), 'Failed to parse time string') !== false) {
            $response = $this->statusCode(Response::HTTP_BAD_REQUEST, [], 'Erreur de format de date.');
        } elseif ($exception instanceof CircularReferenceException) {
            $response = $this->statusCode(Response::HTTP_INTERNAL_SERVER_ERROR, [], 'CircularReferenceException.');
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
