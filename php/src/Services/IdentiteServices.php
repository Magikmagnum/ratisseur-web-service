<?php

namespace App\Services;

use App\Entity\Identite;
use App\Controller\AbstractController;
use App\Repository\IdentiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class IdentiteServices extends AbstractController
{
    private $identiteRepository;
    private $em;
    private $val;

    public function __construct(IdentiteRepository $identiteRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->identiteRepository = $identiteRepository;
        $this->em = $entityManager;
        $this->val = $validator;
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

    public function put(Request $request): Response
    {
        if ($user = $this->getUser()) {
            $validation = $this->validerEtSauvegarderEntity(
                $this->initialiser(
                    $this->obtenirIdentiteUtilisateur($user),
                    json_decode($request->getContent(), true)
                )
            );
            return $this->json($validation['response'], $validation['response']['status'], [], [
                "groups" => $validation['validation'] ? "read:identite:item" : null
            ]);
        }
        return $this->json(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
    }

    /**
     * Récupère l'identité associée à un utilisateur.
     * Si aucune identité n'est trouvée pour l'utilisateur donné, une nouvelle instance d'Identite est créée et retournée
     * @param UserInterface $user L'utilisateur pour lequel récupérer l'identité.
     * @return Identite L'objet Identite associé à l'utilisateur ou une nouvelle instance d'Identite.
     */
    private function obtenirIdentiteUtilisateur(UserInterface $user): Identite
    {
        return $this->identiteRepository->findOneBy(['user' => $user]) ?? new Identite();
    }

    /**
     * Valide l'entité et sauvgarde si aucune erreur n'est trouvée, sinon retourne un tableau d'erreurs.
     * @param object $entity L'entité à valider et à sauvegarder.
     * @return object|array Retourne true si aucune erreur n'est trouvée, sinon un tableau d'erreurs.
     */
    public function validerEtSauvegarderEntity(object $entity)
    {
        // Valider l'entité
        if ($validationErrors = $this->validationEntity($entity)) {
            // Retourner les erreurs de validation
            return ['response' => $validationErrors, 'validation' => false];
        }

        // Sauvegarder l'entité en base de données
        $entityManager = $this->em;
        $entityManager->persist($entity);
        $entityManager->flush();

        // Retourner true si tout est bon
        return ['response' => $this->statusCode(Response::HTTP_CREATED, $entity), 'validation' => true];
    }

    /**
     * Initialise les données fournies et met à jour l'objet donné.
     * @param object $entity L'objet à mettre à jour (doit avoir des méthodes de setter).
     * @param array $data Les données à assigner à l'objet.
     * @return object L'objet mis à jour.
     */
    public function initialiser(object $entity, array $data): object
    {
        // Boucle à travers les données pour mettre à jour l'entité
        foreach ($data as $field => $value) {
            $method = 'set' . ucfirst($field); // Générer le nom de la méthode setter
            // Vérifier si la méthode existe
            if (method_exists($entity, $method)) {
                // Gérer les cas où le champ se termine par 'At' (date)
                if (substr($field, -2) === 'At') {
                    try {
                        $value = new \DateTimeImmutable($value);
                    } catch (\Exception $e) {
                        // Gestion d'erreur pour une date invalide (peut-être journaliser)
                        continue; // Passer à l'itération suivante si la date est invalide
                    }
                }
                // Appeler la méthode setter
                $entity->$method($value);
            }
        }

        // Si l'entité n'a pas d'utilisateur défini, on l'associe à l'utilisateur courant
        if (method_exists($entity, 'getUser') && method_exists($entity, 'setUser') && !$entity->getUser()) {
            $entity->setUser($this->getUser());
        }

        // Retourne l'objet mis à jour
        return $entity;
    }

    /**
     * Valide les données de l'objet par rapport aux contraintes de la classe Entity.
     *
     * @param object $entity L'objet à valider.
     * @param array|null $errors Les erreurs existantes, le cas échéant.
     * @return array|false Un tableau d'erreurs ou false s'il n'y a pas d'erreur.
     */
    public function validationEntity(object $entity, ?array $errors = []): array | false
    {
        $validator = $this->val->validate($entity);
        if (count($validator) > 0) {
            $ormValidationError = [];
            foreach ($validator as $val) {
                $ormValidationError[] = [
                    'field' => $val->getPropertyPath(),
                    'message' => $val->getMessage(),
                ];
            }
            if ($errors) {
                return $this->statusCode(Response::HTTP_BAD_REQUEST, array_merge($errors, $ormValidationError));
            }
            return $this->statusCode(Response::HTTP_BAD_REQUEST, $ormValidationError);
        }

        if ($errors) {
            return $this->statusCode(Response::HTTP_BAD_REQUEST, $errors);
        }
        return false;
    }
}
