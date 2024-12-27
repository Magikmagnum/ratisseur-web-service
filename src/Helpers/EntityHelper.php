<?php

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EntityHelper
{
    protected ValidatorInterface $validator;
    protected EntityManagerInterface $entityManager;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * Valide une entité en fonction de ses contraintes de validation.
     *
     * @param object $entity L'entité à valider.
     * @param array|null $existingErrors Les erreurs déjà collectées, le cas échéant.
     * @return array|false Un tableau d'erreurs ou false s'il n'y a pas d'erreurs.
     */
    public function validate(object $entity, ?array $existingErrors = []): array|false
    {
        $validationResults = $this->validator->validate($entity);

        if (count($validationResults) > 0) {
            $validationErrors = array_map(static function ($violation) {
                return [
                    'field' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                ];
            }, iterator_to_array($validationResults));

            $allErrors = $existingErrors ? array_merge($existingErrors, $validationErrors) : $validationErrors;

            return $this->buildErrorResponse(Response::HTTP_BAD_REQUEST, $allErrors);
        }

        return $existingErrors ? $this->buildErrorResponse(Response::HTTP_BAD_REQUEST, $existingErrors) : false;
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
        $manager = $this->entityManager;

        if ($isNew) {
            $manager->persist($entity);
        }

        $manager->flush();
        return true;
    }

    /**
     * Supprime une entité de la base de données.
     *
     * @param object $entity L'entité à supprimer.
     * @return bool
     */
    public function deleteEntity(object $entity): bool
    {
        $manager = $this->entityManager;
        $manager->remove($entity);
        $manager->flush();
        return true;
    }

    /**
     * Récupère le gestionnaire d'entités.
     *
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * Construit une réponse d'erreur.
     *
     * @param int $statusCode Code HTTP.
     * @param array $errors Liste des erreurs.
     * @return array Structure de la réponse.
     */
    private function buildErrorResponse(int $statusCode, array $errors): array
    {
        return [
            'status' => $statusCode,
            'success' => false,
            'errors' => $errors,
        ];
    }
}
