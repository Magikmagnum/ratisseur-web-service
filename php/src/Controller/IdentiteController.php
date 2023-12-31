<?php

// src/Controller/IdentiteController.php

namespace App\Controller;

use App\Entity\Identite;
use App\Form\IdentiteType;
use App\Repository\IdentiteRepository;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/identite")
 */
class IdentiteController extends AbstractController
{
    /**
     * @Route("/", name="identite_index", methods={"GET"})
     */
    public function index(IdentiteRepository $identiteRepository): Response
    {
        // Cette action ne nécessite pas d'autorisation spécifique
        $identites = $identiteRepository->findAll();
        $response = $this->statusCode(Response::HTTP_OK, $identites);
        return $this->json($response, $response["status"], [], ["groups" => "read:identite:list"]);
    }

    /**
     * @Route("/new", name="identite_new", methods={"POST"})
     * @IsGranted("ROLE_USER")  // Autorisation pour l'ajout
     */
    public function new(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $identite = new Identite();

        // Ici, vous devriez attribuer l'utilisateur actuel à l'identité, par exemple, si l'utilisateur est connecté.
        // $identite->setUser($this->getUser());

        // Créer le formulaire en utilisant IdentiteType
        $form = $this->createForm(IdentiteType::class, $identite);
        // Soumettre les données au formulaire
        $form->submit($data);

        // Si des erreurs de validation sont trouvées, renvoyer une réponse avec les erreurs
        if ($validationErrors = $this->validateEntity($identite)) {
            return $this->json($validationErrors, $validationErrors['status']);
        }

        // Sauvegarder l'entité en base de données
        $entityManager = $this->getManager();
        $entityManager->persist($identite);
        $entityManager->flush();

        // Répondre avec succès
        $response = $this->statusCode(Response::HTTP_CREATED, $identite);
        return $this->json($response, $response["status"], [], ["groups" => "read:identite:iteme"]);
    }

    /**
     * @Route("/{id}", name="identite_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Identite $identite): Response
    {
        // Vous pouvez personnaliser la logique d'affichage ici
        // Répondre avec succès
        $response = $this->statusCode(Response::HTTP_OK, $identite);
        return $this->json($response, $response["status"], [], ["groups" => "read:identite:item"]);
    }


    /**
     * @Route("/{id}/edit", name="identite_edit", methods={"PUT"})
     * @IsGranted("EDIT", subject="identite")  // Autorisation pour la modification
     */
    public function edit(Request $request, Identite $identite): Response
    {
        // Ici, vous devriez vérifier si l'utilisateur actuel est autorisé à modifier cette identité.
        // if (!$this->isGranted('EDIT', $identite)) {
        //     return $this->json($this->responseService->error('Vous n\'avez pas la permission de modifier cette identité.'));
        // }

        // Convertir le contenu JSON en tableau associatif
        $data = json_decode($request->getContent(), true);

        // Soumettre les données au formulaire
        $form = $this->createForm(IdentiteType::class, $identite);
        $form->submit($data, false);

        // Valider l'entité
        if ($validationErrors = $this->validateEntity($identite)) {
            // Si des erreurs de validation sont trouvées, renvoyer une réponse avec les erreurs
            return $this->json($validationErrors, $validationErrors['status']);
        }

        // Sauvegarder l'entité modifiée en base de données
        $this->getManager()->flush();

        // Répondre avec succès
        $response = $this->statusCode(Response::HTTP_OK, $identite);
        return $this->json($response, $response["status"], [], ["groups" => "read:identite:item"]);
    }

    /**
     * @Route("/{id}", name="identite_delete", methods={"DELETE"})
     * @IsGranted("DELETE", subject="identite")  // Autorisation pour la suppression
     */
    public function delete(Identite $identite): Response
    {
        // Ici, vous devriez vérifier si l'utilisateur actuel est autorisé à supprimer cette identité.
        // if (!$this->isGranted('DELETE', $identite)) {
        //     return $this->json($this->responseService->error('Vous n\'avez pas la permission de supprimer cette identité.'));
        // }

        $entityManager = $this->getManager();
        $entityManager->remove($identite);
        $entityManager->flush();

        // Répondre avec succès
        $response = $this->statusCode(Response::HTTP_NO_CONTENT, $identite);
        return $this->json($response, $response['status']);
    }
}
