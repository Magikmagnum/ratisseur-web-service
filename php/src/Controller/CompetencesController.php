<?php

namespace App\Controller;

use App\Entity\Competences;
use App\Repository\CompetencesRepository;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/competences")
 */
class CompetencesController extends AbstractController
{
    /**
     * @Route("", name="competences_index", methods={"GET"})
     */
    public function index(CompetencesRepository $competencesRepository): Response
    {
        $competences = $competencesRepository->findAll();
        $response = $this->statusCode(Response::HTTP_OK, $competences);
        return $this->json($response, $response["status"], [], ["groups" => "read:competence:list"]);
    }

    /**
     * @Route("", name="competences_new", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['label']) && !empty($data['label'])) {

            $competence = new Competences();
            $competence->setLabel($data['label']);
            $data['description'] && $competence->setDescription($data['description']);
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
     * @Route("/{id}", name="competences_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Competences $competence): Response
    {
        $response = $this->statusCode(Response::HTTP_OK, $competence);
        return $this->json($response, $response["status"], [], ["groups" => "read:competence:item"]);
    }

    /**
     * @Route("/{id}", name="competences_edit", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Competences $competence): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data)) {

            $data['label'] && $competence->setLabel($data['label']);
            $data['label'] && $competence->setDescription($data['description']);
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
     * @Route("/{id}", name="competences_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Competences $competence): Response
    {
        $entityManager = $this->getManager();
        $entityManager->remove($competence);
        $entityManager->flush();

        $response = $this->statusCode(Response::HTTP_OK);
        return $this->json($response, $response['status']);
    }
}
