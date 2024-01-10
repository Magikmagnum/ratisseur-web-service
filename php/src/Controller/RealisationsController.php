<?php

// src/Controller/RealisationsController.php

namespace App\Controller;

use App\Entity\Realisations;
use App\Repository\RealisationsRepository;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
     * @IsGranted("ROLE_USER")
     */
    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['label']) && !empty($data['label'])) {

            $erreurs = [];

            $realisation = new Realisations();
            $realisation->setLabel($data['label']);
            $realisation->setDescription($data['description']);
            $realisation->setCreatedAt(new \DateTimeImmutable());

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

        $response = $this->statusCode(Response::HTTP_BAD_REQUEST);
        return $this->json($response, $response['status']);
    }

    /**
     * @Route("/{id}", name="realisations_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
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
     * @IsGranted("POST_EDIT", subject="realisation")  // Autorisation pour la modification
     */
    public function edit(Request $request, Realisations $realisation): Response
    {
        // Ici, vous devriez vérifier si l'utilisateur actuel est autorisé à modifier cette réalisation.
        if (!$this->isGranted('POST_EDIT', $realisation)) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN, 'Vous n\'avez pas la permission de modifier cette réalisation.');
            return $this->json($response, $response['status']);
        }

        // Convertir le contenu JSON en tableau associatif
        $data = json_decode($request->getContent(), true);

        if (isset($data)) {

            $data['label'] && $realisation->setLabel($data['label']);
            $data['description'] && $realisation->setDescription($data['description']);

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
     * @IsGranted("POST_DELETE", subject="realisation")
     * @IsGranted("ROLE_USER")
     */
    public function delete(Realisations $realisation): Response
    {
        // Ici, vous devriez vérifier si l'utilisateur actuel est autorisé à supprimer cette réalisation.
        if (!$this->isGranted('POST_DELETE', $realisation)) {
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
