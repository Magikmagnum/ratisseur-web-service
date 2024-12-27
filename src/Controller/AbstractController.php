<?php

namespace App\Controller;

use EntityHelper;
use App\Helpers\CheckHelper;
use App\Helpers\HttpResponseHelper;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    protected $check;
    protected $listener;
    protected $entityHelper;
    protected $jWTManager;

    public function __construct(EntityHelper $entityHelper, CheckHelper $checkHelper, JWTTokenManagerInterface $jWTManager)
    {
        $this->check = $checkHelper;
        $this->jWTManager = $jWTManager;
        $this->entityHelper = $entityHelper;
    }

    public function getDatime($var = 'now')
    {
        return new \DateTime($var, new \DateTimeZone('Africa/Libreville'));
    }

    /**
     * Valide les données de l'objet par rapport aux contraintes de la classe Entity.
     *
     * @param object $entity L'objet à valider.
     * @param array|null $errors Les erreurs existantes, le cas échéant.
     * @return array|false Un tableau d'erreurs ou false s'il n'y a pas d'erreur.
     */
    public function validateEntity(object $entity, ?array $errors = []): array | false
    {
        return $this->entityHelper->validate($entity, $errors);
    }

    // La methode fournit l'objet entity manager de Doctrine
    public function getManager()
    {
        return $this->entityHelper->getEntityManager();
    }

    /**
     * Enregistre ou met à jour une entité dans la base de données.
     *
     * @param object $entity L'entité à sauvegarder.
     * @param bool $isNew Indique si l'entité est nouvelle (true pour persist).
     * @return bool
     */
    public function saveEntity(object $entity, bool $isNew = false): bool
    {
        return $this->entityHelper->saveEntity($entity, $isNew);
    }

    /**
     * Supprime une entité de la base de données.
     *
     * @param object $entity L'entité à supprimer.
     * @return bool
     */
    public function deleteEntity(object $entity): bool
    {
        return $this->entityHelper->deleteEntity($entity);
    }


    /**
     * return a response type array
     *  gestion des erreurs HTTP responses
     *
     * @param int $statusCode
     * @param $data une instance de l'entity
     * @param string|null $message
     * @return 
     */
    public function statusCode($statusCode, $data = [], string $message = null)
    {
        $statusCode = new HttpResponseHelper();
        return $statusCode->buildResponse($statusCode, $data, $message);
    }
}
