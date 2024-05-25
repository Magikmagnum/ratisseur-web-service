<?php

namespace App\Controller;

use App\Entity\Competences;
use App\Entity\CompetencesListe;
use App\Repository\CompetencesRepository;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CompetencesListeRepository;
use App\Services\CompetencesServices;

/**
 * @Route("")
 */
class CompetencesController extends AbstractController
{
    /**
     * @Route("/competences", name="competences_index", methods={"GET"})
     */
    public function index(CompetencesRepository $competencesRepository): Response
    {
        $competences = $competencesRepository->findAll();
        $response = $this->statusCode(Response::HTTP_OK, $competences);
        return $this->json($response, $response["status"], [], ["groups" => "read:competence:list"]);
    }

    /**
     * @Route("/competences/{id}", name="competences_show", methods={"GET"})
     */
    public function show(Competences $competence): Response
    {
        $response = $this->statusCode(Response::HTTP_OK, $competence);
        return $this->json($response, $response["status"], [], ["groups" => "read:competence:item"]);
    }

    /**
     * @Route("/competences_user", name="competences_new", methods={"POST"})
     */
    public function add(Request $request, CompetencesListeRepository $competencesListeRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['label']) && !empty($data['label'])) {
            $competence = new Competences();
            $entityManager = $this->getManager();

            if (isset($data['label'])) {
                if (!$competenceListe = $competencesListeRepository->findOneBy(['label' => $data['label']])) {
                    $competenceListe = new CompetencesListe();
                    $competenceListe->setLabel($data['label']);

                    if ($validationErrors = $this->validateEntity($competenceListe)) {
                        return $this->json($validationErrors, $validationErrors['status']);
                    }
                    $entityManager->persist($competenceListe);
                }
                $competence->setLabel($competenceListe);
            }

            isset($data['description']) && $competence->setDescription($data['description']);
            $competence->setUser($this->getUser());

            // Si des erreurs de validation sont trouvées, renvoyer une réponse avec les erreurs
            if ($validationErrors = $this->validateEntity($competence)) {
                return $this->json($validationErrors, $validationErrors['status']);
            }

            $entityManager = $this->getManager();
            $entityManager->persist($competence);
            $entityManager->flush();

            $response = $this->statusCode(Response::HTTP_CREATED, $competence);
            return $this->json($response, $response["status"], [], ["groups" => "read:competence:item"]);
        }

        $response = $this->statusCode(Response::HTTP_BAD_REQUEST, 'Le label de la compétence est obligatoire');
        return $this->json($response, $response['status']);
    }

    /**
     * @Route("/competences_user/{id}", name="competences_edit", methods={"PUT"})
     */
    public function edit(Request $request, Competences $competence, CompetencesListeRepository $competencesListeRepository): Response
    {
        // Ici, nous vérifions si l'utilisateur actuel est autorisé à modifier cette identité.
        if (!$this->isGranted('EDIT', $competence)) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN, 'Vous n\'avez pas la permission de modifier cette identité.');
            return $this->json($response, $response['status']);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data)) {

            $entityManager = $this->getManager();

            if (isset($data['label'])) {
                if (!$competenceListe = $competencesListeRepository->findOneBy(['label' => $data['label']])) {
                    $competenceListe = new competencesListe();
                    $competenceListe->setLabel($data['label']);

                    if ($validationErrors = $this->validateEntity($competenceListe)) {
                        return $this->json($validationErrors, $validationErrors['status']);
                    }
                    $entityManager->persist($competenceListe);
                }
                $competence->setLabel($competenceListe);
            }

            isset($data['description']) && $competence->setDescription($data['description']);
            $competence->setModifyAt(new \DateTimeImmutable());

            if ($validationErrors = $this->validateEntity($competence)) {
                return $this->json($validationErrors, $validationErrors['status']);
            }

            $this->getManager()->flush();

            $response = $this->statusCode(Response::HTTP_OK, $competence);
            return $this->json($response, $response["status"], [], ["groups" => "read:competence:item"]);
        }

        $response = $this->statusCode(Response::HTTP_BAD_REQUEST);
        return $this->json($response, $response['status']);
    }

    /**
     * @Route("/competences_user/{id}", name="competences_delete", methods={"DELETE"})
     */
    public function delete(Competences $competence): Response
    {
        // Ici, nous vérifions si l'utilisateur actuel est autorisé à modifier cette identité.
        if (!$this->isGranted('DELETE', $competence)) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN, 'Vous n\'avez pas la permission de modifier cette identité.');
            return $this->json($response, $response['status']);
        }

        $entityManager = $this->getManager();
        $entityManager->remove($competence);
        $entityManager->flush();

        $response = $this->statusCode(Response::HTTP_OK);
        return $this->json($response, $response['status']);
    }

    /**
     * @Route("/competences_client", name="competences_exclus_curent_user", methods={"GET"})
     */
    public function listCompetencesExclusCurrentUser(CompetencesServices $competencesServices): Response
    {
        // Ici, nous vérifions si l'utilisateur actuel est autorisé à modifier cette identité.
        if (!$user = $this->getUser()) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN);
            return $this->json($response, $response['status']);
        }
        return $competencesServices->find_all_competences_except_user($user->getId());
    }

    /**
     * @Route("/competences_client/{id}", name="competences_client_index", methods={"GET"})
     */
    public function listCompetencesByUserId(int $id, CompetencesServices $competencesServices): Response
    {
        return $competencesServices->find_all_competences_by_user($id);
    }
}
