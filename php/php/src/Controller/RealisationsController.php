<?php

// src/Controller/RealisationsController.php

namespace App\Controller;

use App\Entity\Realisations;
use App\Repository\RealisationsRepository;
use App\Controller\AbstractController;
use App\Repository\CompetencesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/realisation")
 */
class RealisationsController extends AbstractController
{
    /**
     * @Route("", name="realisations_index", methods={"GET"})
     */
    public function index(RealisationsRepository $realisationsRepository): Response
    {
        // Cette action ne nécessite pas d'autorisation spécifique
        $realisations = $realisationsRepository->findAll();
        $response = $this->statusCode(Response::HTTP_OK, $realisations);
        return $this->json($response, $response["status"], [], ["groups" => "read:realisation:list"]);
    }

    /**
     * @Route("", name="realisations_new", methods={"POST"})
     */
    public function add(Request $request, CompetencesRepository $competencesRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['competenceId'])) {

            $erreurs = [];

            $competence = $competencesRepository->find($data['competenceId']);
            // Ici, nous vérifions si l'utilisateur actuel est autorisé à modifier cette identité.
            if (!$this->isGranted('EDIT', $competence)) {
                $response = $this->statusCode(Response::HTTP_FORBIDDEN, 'Vous n\'avez pas la permission de créer cette réalisation.');
                return $this->json($response, $response['status']);
            }

            $realisation = new Realisations;
            $realisation->setCompetence($competence);
            isset($data['label']) && $realisation->setLabel($data['label']); //$erreurs[] = ['field' => 'label', 'message' => 'Veuillez remplir ce champ'];
            isset($data['description']) && $realisation->setDescription($data['description']);

            // Si des erreurs de validation sont trouvées, renvoyer une réponse avec les erreurs
            if ($validationErrors = $this->validateEntity($realisation, $erreurs)) {
                return $this->json($validationErrors, $validationErrors['status']);
            }

            // Sauvegarder l'entité en base de données
            $entityManager = $this->getManager();
            $entityManager->persist($realisation);
            $entityManager->flush();

            // Répondre avec succès
            $response = $this->statusCode(Response::HTTP_CREATED, $realisation);
            return $this->json($response, $response["status"], [], ["groups" => "read:realisation:item"]);
        }

        $response = $this->statusCode(Response::HTTP_BAD_REQUEST, [['field' => 'competenceId', 'message' => 'Veuillez remplir ce champ']]);
        return $this->json($response, $response['status']);
    }

    /**
     * @Route("/{id}", name="realisations_show", methods={"GET"})
     */
    public function show(Realisations $realisation): Response
    {
        // Vous pouvez personnaliser la logique d'affichage ici
        // Répondre avec succès
        $response = $this->statusCode(Response::HTTP_OK, $realisation);
        return $this->json($response, $response["status"], [], ["groups" => "read:realisation:item"]);
    }

    /**
     * @Route("/{id}", name="realisations_edit", methods={"PUT"})
     */
    public function edit(Request $request, Realisations $realisation): Response
    {
        // Ici, vous devriez vérifier si l'utilisateur actuel est autorisé à modifier cette réalisation.
        if (!$this->isGranted('EDIT', $realisation)) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN, 'Vous n\'avez pas la permission de modifier cette réalisation.');
            return $this->json($response, $response['status']);
        }

        // Convertir le contenu JSON en tableau associatif
        $data = json_decode($request->getContent(), true);

        if (isset($data)) {

            isset($data['label']) && $realisation->setLabel($data['label']);
            isset($data['description']) && $realisation->setDescription($data['description']);

            // Valider l'entité
            if ($validationErrors = $this->validateEntity($realisation)) {
                // Si des erreurs de validation sont trouvées, renvoyer une réponse avec les erreurs
                return $this->json($validationErrors, $validationErrors['status']);
            }

            // Sauvegarder l'entité modifiée en base de données
            $this->getManager()->flush();

            // Répondre avec succès
            $response = $this->statusCode(Response::HTTP_OK, $realisation);
            return $this->json($response, $response["status"], [], ["groups" => "read:realisation:item"]);
        }

        $response = $this->statusCode(Response::HTTP_BAD_REQUEST);
        return $this->json($response, $response['status']);
    }


    /**
     * @Route("/{id}", name="realisations_delete", methods={"DELETE"})
     */
    public function delete(Realisations $realisation): Response
    {
        // Ici, vous devriez vérifier si l'utilisateur actuel est autorisé à supprimer cette réalisation.
        if (!$this->isGranted('DELETE', $realisation)) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN, 'Vous n\'avez pas la permission de supprimer cette réalisation.');
            return $this->json($response, $response['status']);
        }

        $entityManager = $this->getManager();
        $entityManager->remove($realisation);
        $entityManager->flush();

        // Répondre avec succès
        $response = $this->statusCode(Response::HTTP_OK);
        return $this->json($response, $response['status']);
    }
}
