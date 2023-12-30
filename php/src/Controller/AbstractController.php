<?php

namespace App\Controller;

use OpenApi\Annotations as OA;
use App\Controller\Helpers\CheckHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

/**
 * 
 * 
 * @OA\Parameter(
 *      name="id",
 *      in="path",
 *      description="ID de la resource",
 *      required=true,
 *      @OA\Schema(type="integer"),
 * )
 * 
 * @OA\Schema(
 *      schema="Created",
 *      description="Created",
 *      @OA\Property(property="status", type="integer", example=201),
 *      @OA\Property(type="boolean", property="success", example=true),
 *      @OA\Property(property="message", type="string", example="Ressource créer avec succès"),
 *     
 * )
 * 
 * 
 * @OA\Schema(
 *      schema="Success",
 *      description="Success",
 *      @OA\Property(property="status", type="integer", example=200),
 *      @OA\Property(type="boolean", property="success", example=true),
 *      @OA\Property(property="message", type="string", example="Requète effectué avec succès"),
 *     
 * )
 * 
 * 
 * @OA\Response(
 *  response="NotFound",
 *  @OA\JsonContent(
 *      @OA\Property(property="status", type="integer", example=404),
 *      @OA\Property(type="boolean", property="success", example=false),
 *      @OA\Property(property="message", type="string", example="Ressource inexistante"),
 *  )
 * ),
 * 
 * 
 * 
 * @OA\Response(
 *  response="Unauthorized",
 *  @OA\JsonContent(
 *      @OA\Property(property="status", type="integer", example=401),
 *      @OA\Property(type="boolean", property="success", example=false),
 *      @OA\Property(property="message", type="string", example="Impossible de vous authentifier"),
 *  )
 * ),
 * 
 * 
 * 
 * @OA\Response(
 *  response="BadRequest",
 *  @OA\JsonContent(
 *      @OA\Property(property="status", type="integer", example=400),
 *      @OA\Property(type="boolean", property="success", example=false),
 *      @OA\Property(property="message", type="string", example="Requète invalide"),
 *  )
 * ),
 * 
 * 
 * 
 * @OA\Response(
 *  response="ForBidden",
 *  @OA\JsonContent(
 *      @OA\Property(property="status", type="integer", example=403),
 *      @OA\Property(type="boolean", property="success", example=false),
 *      @OA\Property(property="message", type="string", example="Vous n'avez pas les droits requis"),
 *  )
 * ),
 * 
 * 
 * 
 * @OA\SecurityScheme(bearerFormat="JWT", type="apiKey", securityScheme="bearer"),
 */
class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    protected $check;
    protected $listener;
    protected $prefix;
    protected $sufix;
    protected $validator;
    protected $jWTManager;
    protected $entityManager;

    public function __construct(ValidatorInterface $validator, ManagerRegistry $entityManager, CheckHelper $checkHelper, JWTTokenManagerInterface $jWTManager)
    {
        $this->check = $checkHelper;
        $this->jWTManager = $jWTManager;
        $this->prefix = "efdjflkxog5f6f@9gds2157b3";
        $this->sufix = "28d54grg!fv4d5g4eq5v5gvsdf";
        $this->entityManager = $entityManager;
        $this->validator = $validator;
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
        // Valider l'entité en utilisant le validateur Symfony
        $validator = $this->validator->validate($entity);

        // Si des erreurs de validation sont trouvées
        if (count($validator) > 0) {
            $ormValidationError = [];

            // Parcourir chaque violation et créer un tableau associatif pour chaque erreur
            foreach ($validator as $val) {
                $ormValidationError[] = [
                    'field' => $val->getPropertyPath(),
                    'message' => $val->getMessage()
                ];
            }

            // Si des erreurs existent déjà, fusionner avec les erreurs de validation
            if ($errors) {
                return $this->statusCode(Response::HTTP_BAD_REQUEST, array_merge($errors, $ormValidationError));
            }

            // Retourner les erreurs de validation si aucune erreur existante
            return $this->statusCode(Response::HTTP_BAD_REQUEST, $ormValidationError);
        }

        // Si des erreurs existent déjà, retourner ces erreurs
        if ($errors) {
            return $this->statusCode(Response::HTTP_BAD_REQUEST, $errors);
        }

        // Aucune erreur détectée, retourner false
        return false;
    }













    // La methode fournit l'objet entity manager de Doctrine
    public function getManager()
    {
        return $this->entityManager->getManager();
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
        switch ($statusCode) {

            case Response::HTTP_CREATED:

                $message === null && $message = "Ressource créee avec succès";
                return $this->response(true, $statusCode, $data, $message);

            case Response::HTTP_OK:

                $message === null && $message = "Operation reussie";
                return $this->response(true, $statusCode, $data, $message);

            case Response::HTTP_ACCEPTED:
                $message === null && $message = "La demande a été acceptée pour traitement";
                return $this->response(true, $statusCode, $data, $message);

            case Response::HTTP_NO_CONTENT:
                $message === null && $message = "Pas de contenu à renvoyer";
                return $this->response(true, $statusCode, $data, $message);

            case Response::HTTP_BAD_REQUEST:

                $message === null && $message = "Requète invalide";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_UNAUTHORIZED:

                $message === null && $message = "Impossible de vous authentifier, veuillez vous connecter";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_FORBIDDEN:

                $message === null && $message = "Vous n'avez pas les droits requis pour continuer cette action";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_NOT_FOUND:

                $message === null && $message = "Route ou ressource inexistante, vérifier le lien de la requête";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_NOT_MODIFIED:

                $message === null && $message = "Ressource non modifier";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_METHOD_NOT_ALLOWED:
                $message === null && $message = "Méthode non autorisée pour cette ressource";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_CONFLICT:
                $message === null && $message = "Conflit lors du traitement de la demande";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_PRECONDITION_FAILED:
                $message === null && $message = "La condition préalable à la demande a échoué";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_UNSUPPORTED_MEDIA_TYPE:
                $message === null && $message = "Type de média non supporté pour cette ressource";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_INTERNAL_SERVER_ERROR:
                $message === null && $message = "Erreur interne du serveur";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_SERVICE_UNAVAILABLE:
                $message === null && $message = "Service non disponible, veuillez réessayer plus tard";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_NOT_ACCEPTABLE:
                $message === null && $message = "Le serveur ne peut pas produire une réponse conforme aux types acceptés indiqués dans l'en-tête de la demande";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_REQUEST_TIMEOUT:
                $message === null && $message = "La demande a expiré ou le serveur a pris trop de temps à répondre";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_GONE:
                $message === null && $message = "La ressource demandée n'est plus disponible et aucune redirection n'est connue";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_LENGTH_REQUIRED:
                $message === null && $message = "La longueur de la demande requise n'a pas été spécifiée";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_REQUEST_ENTITY_TOO_LARGE:
                $message === null && $message = "La taille de la demande est trop grande pour être traitée par le serveur";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_UNPROCESSABLE_ENTITY:
                $message === null && $message = "La requète demande est invalide ou a des champs manquants";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_LOCKED:
                $message === null && $message = "La ressource est verrouillée, vous ne pouvez pas effectuer cette opération actuellement";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_FAILED_DEPENDENCY:
                $message === null && $message = "L'opération a échoué en raison d'une dépendance non satisfaite";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_UPGRADE_REQUIRED:
                $message === null && $message = "La mise à niveau du client est nécessaire pour traiter la demande";
                return $this->response(false, $statusCode, $data, $message);

            case Response::HTTP_PRECONDITION_REQUIRED:
                $message === null && $message = "La condition préalable à la demande est requise et non satisfaite";
                return $this->response(false, $statusCode, $data, $message);

                // Ajoutez d'autres cas selon vos besoins

            default:
                // Cas par défaut pour d'autres codes de statut non gérés
                $message === null && $message = "Code de statut non géré";
                return $this->response(false, $statusCode, $data, $message);
        }
    }





    ///-------------------  private methods --------------------------------






    private function response($success, $statusCode, $data = [], $message = null)
    {
        $response = ["status" => $statusCode, "success" => $success];
        $data ? $response["data"] = $data : null;
        $message ? $response["message"] = $message : null;
        return $response;
    }

    protected function idEncode($id)
    {
        return $this->prefix . $id . $this->sufix;
    }

    protected function idDecode($id)
    {
        $sansPrefix = substr($id, strlen($this->prefix));
        $sansSufix = substr($sansPrefix, 0, -strlen($this->sufix));
        return $sansSufix;
    }
}
