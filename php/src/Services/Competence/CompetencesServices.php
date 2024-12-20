<?php

namespace App\Services\Competence;

use RuntimeException;
use App\Entity\Competences;
use App\Helpers\ImageUploader;
use App\Services\UserServices;
use App\Entity\CompetencesListe;
use App\Controller\AbstractController;
use App\Repository\CompetencesRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CompetencesListeRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Competence\CompetenceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Persistence\ManagerRegistry;

enum MessageError: string
{
    case NO_FILE = "No file uploaded";
    case UPLOAD_FAILED = "File upload failed";
}

class CompetencesServices extends AbstractController implements CompetenceInterface
{
    const  CUSTOME_IMAGE_DIRECTORY = "images/competences";
    const  CUSTOME_IMAGE_NAME = "competences_";

    private CompetencesListeRepository $competencesListeRepository;
    private ImageUploader $imageUploader;
    private CompetencesRepository $competencesRepository;
    private UserServices $userServices;
    protected $validator;
    protected $entityManager;

    public function __construct(ValidatorInterface $validator, ManagerRegistry $entityManager, CompetencesListeRepository $competencesListeRepository, ImageUploader $imageUploader, CompetencesRepository $competencesRepository, UserServices $userServices)
    {
        $this->competencesRepository = $competencesRepository;
        $this->userServices = $userServices;
        $this->competencesListeRepository = $competencesListeRepository;
        $this->imageUploader = $imageUploader;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    public function creerUneCompetence(Request $request): response
    {
        $competence = $this->hydrateEntity(new Competences(), $request);

        if ($validationErrors = $this->validateEntities($competence)) {
            return $this->json($validationErrors, $validationErrors['status']);
        }

        $this->sauvegardeEntity($competence, true);
        $response = $this->statusCode(Response::HTTP_CREATED, $competence);
        return $this->json($response, $response["status"], [], ["groups" => "read:competence:item"]);
    }


    public function modifierUneCompetence(Competences $competence, Request $request)
    {
        $competence = $this->hydrateEntity($competence, $request);
        if ($validationErrors = $this->validateEntities($competence)) {
            return $this->json($validationErrors, $validationErrors['status']);
        }
        $this->sauvegardeEntity($competence);
        $response = $this->statusCode(Response::HTTP_OK, $competence);
        return $this->json($response, $response["status"], [], ["groups" => "read:competence:item"]);
    }


    private function hydrateEntity(Competences $competence, Request $request): Competences
    {
        $data = $request->request->all();
        $data['enseigne'] = $request->files->get('enseigne');

        // Assurez-vous que l'utilisateur est défini
        if (!$competence->getUser()) {
            $competence->setUser($this->getUser());
        }

        // Vérifie et assigne le label si présent
        if (isset($data['label']) && !empty($data['label'])) {
            $competence->setLabel($this->getCompetenceLabel($data['label']));
        }

        // Vérifie et assigne l'enseigne si présente
        if ($data['enseigne']) {
            $competence->setEnseigne(
                $this->imageUploader->upload($data['enseigne'], self::CUSTOME_IMAGE_DIRECTORY, self::CUSTOME_IMAGE_NAME, $competence->getEnseigne() ?: null)
            );
        }

        // Vérifie et assigne la description si présente
        if (isset($data['description']) && !empty($data['description'])) {
            $competence->setDescription($data['description']);
        }
        return $competence;
    }

    private function getCompetenceLabel(string $label): CompetencesListe
    {
        if (!$competenceListe = $this->competencesListeRepository->findOneBy(['label' => $label])) {
            $competenceListe = new CompetencesListe();
            $competenceListe->setLabel($label);

            if ($validationErrors = $this->validateEntity($competenceListe)) {
                return $this->json($validationErrors, $validationErrors['status']);
            }
            $this->getManager()->entityManager->persist($competenceListe);
        }
        return $competenceListe;
    }





















    /**
     * Sauvegarde une entité dans la base de données.
     * @param object $entity
     * @param bool $persist
     * @return bool
     */
    protected function sauvegardeEntity(object $entity, bool $persist = false): bool
    {
        $em = $this->entityManager->getManager();
        if ($persist) {
            $em->persist($entity);
        }
        $em->flush();
        return true;
    }

    /**
     * Valide les données de l'objet par rapport aux contraintes de l'entité.
     *
     * @param object $entity
     * @param array|null $errors
     * @return array|false
     */
    protected function validateEntities(object $entity, ?array $errors = []): array|false
    {
        // Valider l'entité
        $violations = $this->validator->validate($entity);

        if (count($violations) > 0) {
            $validationErrors = [];

            foreach ($violations as $violation) {
                $validationErrors[] = [
                    'field' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                ];
            }

            $mergedErrors = $errors ? array_merge($errors, $validationErrors) : $validationErrors;
            return $this->statusCode(Response::HTTP_BAD_REQUEST, $mergedErrors);
        }

        if ($errors) {
            return $this->statusCode(Response::HTTP_BAD_REQUEST, $errors);
        }

        return false;
    }








    public function supprimerUneCompetence(Request $request) {}
    public function listerLesCompetences(Request $request) {}
    public function listerLesCompetencesParUtilisateur(Request $request) {}
    public function listerLesCompetencesSolicite(Request $request) {}







    public function find_all_competences_by_user(Int $id): Response
    {
        $response = $this->statusCode(Response::HTTP_OK, $this->competencesRepository->findBy(['user' => $this->userServices->get_user_by_id($id)]));
        return $this->json($response, $response['status'], [], ['groups' => 'read:competence:list:user']);
    }

    public function find_all_competences_except_user(Int $id): Response
    {
        // Récupérer l'utilisateur par son ID
        // Retourner la réponse JSON
        $response = $this->statusCode(Response::HTTP_OK, $this->competencesRepository->findAllExcepteUser($this->userServices->get_user_by_id($id)));
        return $this->json($response, $response['status'], [], ['groups' => 'read:competence:list']);
    }
}
