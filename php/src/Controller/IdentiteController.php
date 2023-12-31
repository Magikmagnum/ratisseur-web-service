<?php

// src/Controller/IdentiteController.php

namespace App\Controller;

use App\Entity\Identite;
use App\Form\IdentiteType;
use App\Repository\IdentiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Service\ResponseService;

/**
 * @Route("/identite")
 */
class IdentiteController extends AbstractController
{
    private ResponseService $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    /**
     * @Route("/", name="identite_index", methods={"GET"})
     */
    public function index(IdentiteRepository $identiteRepository): Response
    {
        // Cette action ne nécessite pas d'autorisation spécifique
        $identites = $identiteRepository->findAll();
        return $this->json($this->responseService->success($identites));
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

        $form = $this->createForm(IdentiteType::class, $identite);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($identite);
            $entityManager->flush();

            return $this->json($this->responseService->success($identite));
        }

        return $this->json($this->responseService->error($form->getErrors(true)));
    }

    /**
     * @Route("/{id}", name="identite_show", methods={"GET"})
     */
    public function show(Identite $identite): Response
    {
        // Cette action ne nécessite pas d'autorisation spécifique pour la visualisation
        return $this->json($this->responseService->success($identite));
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

        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(IdentiteType::class, $identite);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->json($this->responseService->success($identite));
        }

        return $this->json($this->responseService->error($form->getErrors(true)));
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

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($identite);
        $entityManager->flush();

        return $this->json($this->responseService->success(['message' => 'Utilisateur supprimé avec succès.']));
    }
}
