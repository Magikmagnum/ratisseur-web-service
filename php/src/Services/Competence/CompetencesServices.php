<?php

namespace App\Services\Competence;

use App\Entity\Competences;
use App\Helpers\ImageUploadHelper;
use App\Controller\AbstractController;
use App\Repository\CompetencesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Competence\CompetenceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

enum MessageError: string
{
    case NO_FILE = "No file uploaded";
    case UPLOAD_FAILED = "File upload failed";
}

class CompetencesServices extends AbstractController implements CompetenceInterface
{
    const  CUSTOME_IMAGE_DIRECTORY = "images/competences";
    const  CUSTOME_IMAGE_NAME = "competences_";

    private ImageUploadHelper $ImageUploadHelper;
    private CompetencesRepository $competencesRepository;
    private CompetencesListeServices $competencesListeServices;

    public function __construct(CompetencesRepository $competencesRepository, CompetencesListeServices $competencesListeServices, ImageUploadHelper $ImageUploadHelper)
    {
        $this->competencesRepository = $competencesRepository;
        $this->ImageUploadHelper = $ImageUploadHelper;
        $this->competencesListeServices = $competencesListeServices;
    }

    public function creerUneCompetence(Request $request): JsonResponse
    {
        $competence = $this->hydrateEntity(new Competences(), $request);

        if ($validationErrors = $this->validateEntity($competence)) {
            return $this->json($validationErrors, $validationErrors['status']);
        }

        $this->saveEntity($competence, true);
        $response = $this->statusCode(Response::HTTP_CREATED, $competence);
        return $this->json($response, $response["status"], [], ["groups" => "read:competence:item"]);
    }

    public function modifierUneCompetence($id, Request $request): JsonResponse
    {
        $competence = $this->hydrateEntity($this->competencesRepository->find($id), $request);

        // Ici, nous vérifions si l'utilisateur actuel est autorisé à supprimer cette ressource
        if (!$this->isGranted('EDIT', $competence)) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN, 'Vous n\'avez pas la permission de supprimer cette ressource.');
            return $this->json($response, $response['status']);
        }

        if ($validationErrors = $this->validateEntity($competence)) {
            return $this->json($validationErrors, $validationErrors['status']);
        }
        $this->saveEntity($competence);
        $response = $this->statusCode(Response::HTTP_OK, $competence);
        return $this->json($response, $response["status"], [], ["groups" => "read:competence:item"]);
    }

    public function supprimerUneCompetence($id): JsonResponse
    {
        $competence = $this->competencesRepository->find($id);

        if (!$this->isGranted('DELETE', $competence)) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN, 'Vous n\'avez pas la permission de supprimer cette ressource.');
            return $this->json($response, $response['status']);
        }

        $this->ImageUploadHelper->delete($competence->getEnseigne(), self::CUSTOME_IMAGE_DIRECTORY);
        $this->deleteEntity($competence);

        $response = $this->statusCode(Response::HTTP_OK);
        return $this->json($response, $response['status']);
    }

    public function listerLesCompetencesUtilisateur(): JsonResponse
    {
        if (!$user = $this->getUser()) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN);
            return $this->json($response, $response['status']);
        }

        $response = $this->statusCode(
            Response::HTTP_OK,
            $this->competencesRepository->findBy([
                'user' => $user
            ])
        );
        return $this->json($response, $response['status'], [], ['groups' => 'read:competence:list:user']);
    }

    public function listerLesCompetences(): JsonResponse
    {
        if (!$user = $this->getUser()) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN);
            return $this->json($response, $response['status']);
        }

        $response = $this->statusCode(
            Response::HTTP_OK,
            $this->competencesRepository->findAllExcepteUser($user)
        );
        return $this->json($response, $response['status'], [], ['groups' => 'read:competence:list']);
    }

    public function detailCompetences(int $id): JsonResponse
    {
        if (!$user = $this->getUser()) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN);
            return $this->json($response, $response['status']);
        }

        $response = $this->statusCode(
            Response::HTTP_OK,
            $this->competencesRepository->findOneBy(["id" => $id])
        );
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
            $competence->setLabel($this->competencesListeServices->getCompetenceLabel($data['label']));
        }

        // Vérifie et assigne l'enseigne si présente
        if ($data['enseigne']) {
            $competence->setEnseigne(
                $this->ImageUploadHelper->upload($data['enseigne'], self::CUSTOME_IMAGE_DIRECTORY, self::CUSTOME_IMAGE_NAME, $competence->getEnseigne() ?: null)
            );
        }

        // Vérifie et assigne la description si présente
        if (isset($data['description']) && !empty($data['description'])) {
            $competence->setDescription($data['description']);
        }
        return $competence;
    }
}
