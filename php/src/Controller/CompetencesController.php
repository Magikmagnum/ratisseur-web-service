<?php

namespace App\Controller;

use App\Entity\Competences;
use App\Entity\CompetencesListe;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CompetencesListeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Competence\CompetencesServices;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('competences_user')]
class CompetencesController extends AbstractController
{
    #[Route('', name: 'competences_user_index', methods: ['GET'])]
    public function index(CompetencesServices $competencesServices): Response
    {
        // Ici, nous vérifions si l'utilisateur actuel est autorisé à modifier cette identité.
        if (!$user = $this->getUser()) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN);
            return $this->json($response, $response['status']);
        }
        return $competencesServices->find_all_competences_by_user($user->getId());
    }

    #[Route('/{id}', name: 'competences_user_show', methods: ['GET'])]
    public function show(Competences $competence): Response
    {
        /// TODO: ajouter un voter
        $response = $this->statusCode(Response::HTTP_OK, $competence);
        return $this->json($response, $response["status"], [], ["groups" => "read:competence:item"]);
    }

    #[Route('', name: 'competences_user_new', methods: ['POST'])]
    public function add(Request $request, CompetencesServices $competencesServices): Response
    {
        return $competencesServices->creerUneCompetence($request);
    }

    #[Route('/testPicture', name: 'competences_user_id', methods: ['POST'])]
    public function edit(CompetencesServices $competencesServices, Request $request, Competences $competence, CompetencesListeRepository $competencesListeRepository): Response
    {
        return $competencesServices->creerUneCompetence($request);
        //     // Ici, nous vérifions si l'utilisateur actuel est autorisé à modifier cette identité.
        //     if (!$this->isGranted('EDIT', $competence)) {
        //         $response = $this->statusCode(Response::HTTP_FORBIDDEN, 'Vous n\'avez pas la permission de modifier cette identité.');
        //         return $this->json($response, $response['status']);
        //     }

        //     $data = json_decode($request->getContent(), true);

        //     if (isset($data)) {

        //         $entityManager = $this->getManager();

        //         if (isset($data['label'])) {
        //             if (!$competenceListe = $competencesListeRepository->findOneBy(['label' => $data['label']])) {
        //                 $competenceListe = new competencesListe();
        //                 $competenceListe->setLabel($data['label']);

        //                 if ($validationErrors = $this->validateEntity($competenceListe)) {
        //                     return $this->json($validationErrors, $validationErrors['status']);
        //                 }
        //                 $entityManager->persist($competenceListe);
        //             }
        //             $competence->setLabel($competenceListe);
        //         }

        //         isset($data['description']) && $competence->setDescription($data['description']);
        //         $competence->setModifyAt(new \DateTimeImmutable());

        //         if ($validationErrors = $this->validateEntity($competence)) {
        //             return $this->json($validationErrors, $validationErrors['status']);
        //         }

        //         $this->getManager()->flush();

        //         $response = $this->statusCode(Response::HTTP_OK, $competence);
        //         return $this->json($response, $response["status"], [], ["groups" => "read:competence:item"]);
        //     }

        //     $response = $this->statusCode(Response::HTTP_BAD_REQUEST);
        //     return $this->json($response, $response['status']);
    }

    // #[Route('/{id}', name: 'competences_user_delete', methods: ['DELETE'])]
    // public function delete(Competences $competence): Response
    // {
    //     // Ici, nous vérifions si l'utilisateur actuel est autorisé à modifier cette identité.
    //     if (!$this->isGranted('DELETE', $competence)) {
    //         $response = $this->statusCode(Response::HTTP_FORBIDDEN, 'Vous n\'avez pas la permission de modifier cette identité.');
    //         return $this->json($response, $response['status']);
    //     }

    //     $entityManager = $this->getManager();
    //     $entityManager->remove($competence);
    //     $entityManager->flush();

    //     $response = $this->statusCode(Response::HTTP_OK);
    //     return $this->json($response, $response['status']);
    // }



    // #[Route('/testPicture', name: 'competences_testPicture', methods: ['POST'])]
    // public function testPicture(Request $request, CompetencesServices $competencesServices): Response
    // {
    //     $uploadedFile = $request->files->get('enseigne');
    //     $data = json_decode($request->getContent(), true);

    //     if ($uploadedFile) {
    //         $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
    //         $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

    //         try {
    //             $uploadedFile->move(
    //                 $this->getParameter('upload_directory'), // Repertoire de destination
    //                 $newFilename
    //             );

    //             $data['uploadedFilePath'] = $newFilename; // Vous pouvez ajouter cette donnée à la réponse ou la renvoyer comme nécessaire
    //             $response = $this->statusCode(Response::HTTP_OK, $data);
    //             return $this->json($response, $response["status"]);
    //         } catch (FileException $e) {
    //             // Un problème est survenu lors de l'upload
    //             return $this->json([
    //                 'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
    //                 'message' => 'File upload failed: ' . $e->getMessage()
    //             ]);
    //         }
    //     } else {
    //         return $this->json([
    //             'status' => Response::HTTP_BAD_REQUEST,
    //             'message' => 'No file uploaded'
    //         ]);
    //     }
    // }

    /**
     * @Route("/images/{path}", name="image_display", methods={"GET"})
     */
    public function display(string $path): Response
    {
        $filePath = $this->getParameter('upload_directory') . "/" . $path;

        if (!file_exists($filePath)) {
            return new Response("Image not found", Response::HTTP_NOT_FOUND);
        }

        return new Response(
            file_get_contents($filePath),
            Response::HTTP_OK,
            ['Content-Type' => 'image/jpeg'] // Adjust MIME type as needed
        );
    }
}
