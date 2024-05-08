<?php

// src/Controller/IdentiteController.php

namespace App\Controller;

use App\Entity\Identite;
use App\Repository\IdentiteRepository;
use App\Services\IdentiteServices;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/")
 */
class IdentiteController extends AbstractController
{
    /**
     * @Route("identite", name="identite_index", methods={"GET"})
     */
    public function index(IdentiteRepository $identiteRepository, IdentiteServices $identiteServices): Response
    {
        // Cette action ne nécessite pas d'autorisation spécifique
        return $identiteServices->index();
    }

    /**
     * @Route("identite", name="identite_new", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(Request $request): Response
    {
        if ($user = $this->getUser()) {
            $data = json_decode($request->getContent(), true);

            $erreurs = [];

            $identite = new Identite();
            isset($data['sexe']) ? $identite->setSexe($data['sexe']) : $erreurs[] = ['field' => 'sexe', 'message' => 'Ce champ est obligatoire'];
            isset($data['nom']) && $identite->setNom($data['nom']);
            isset($data['naissanceAt']) && $identite->setNaissanceAt(new \DateTimeImmutable($data['naissanceAt']));
            $identite->setUser($user);

            // Si des erreurs de validation sont trouvées, renvoyer une réponse avec les erreurs
            if ($validationErrors = $this->validateEntity($identite, $erreurs)) {
                return $this->json($validationErrors, $validationErrors['status']);
            }

            // Sauvegarder l'entité en base de données
            $entityManager = $this->getManager();
            $entityManager->persist($identite);
            $entityManager->flush();

            // Répondre avec succès
            $response = $this->statusCode(Response::HTTP_CREATED, $identite);
            return $this->json($response, $response["status"], [], ["groups" => "read:identite:item"]);
        }

        $response = $this->statusCode(Response::HTTP_BAD_REQUEST);
        return $this->json($response, $response['status']);
    }

    /**
     * @Route("identite/{id}", name="identite_show", methods={"GET"})
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
     * @Route("identite_user", name="identite_user", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function user(IdentiteServices $identiteServices): Response
    {
        return $identiteServices->user();
    }

    /**
     * @Route("identite/{id}", name="identite_edit", methods={"PUT"})
     * @IsGranted("EDIT", subject="identite")  // Autorisation pour la modification
     */
    public function edit(Request $request, Identite $identite): Response
    {
        // Ici, vous devriez vérifier si l'utilisateur actuel est autorisé à modifier cette identité.
        if (!$this->isGranted('EDIT', $identite)) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN, 'Vous n\'avez pas la permission de modifier cette identité.');
            return $this->json($response, $response['status']);
        }

        // Convertir le contenu JSON en tableau associatif
        $data = json_decode($request->getContent(), true);

        if (isset($data)) {

            ($data['sexe'] === true || $data['sexe'] === false) && $identite->setSexe($data['sexe']);
            $data['nom'] && $identite->setNom($data['nom']);
            $data['naissanceAt'] && $identite->setNaissanceAt(new \DateTimeImmutable($data['naissanceAt']));

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

        $response = $this->statusCode(Response::HTTP_BAD_REQUEST);
        return $this->json($response, $response['status']);
    }

    /**
     * @Route("identite/{id}", name="identite_delete", methods={"DELETE"})
     * @IsGranted("DELETE", subject="identite")
     * @IsGranted("ROLE_USER")
     */
    public function delete(Identite $identite): Response
    {
        // Ici, vous devriez vérifier si l'utilisateur actuel est autorisé à supprimer cette identité.
        if (!$this->isGranted('DELETE', $identite)) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN, 'Vous n\'avez pas la permission de modifier cette identité.');
            return $this->json($response, $response['status']);
        }

        $entityManager = $this->getManager();
        $entityManager->remove($identite);
        $entityManager->flush();

        // Répondre avec succès
        $response = $this->statusCode(Response::HTTP_OK);
        return $this->json($response, $response['status']);
    }
}
