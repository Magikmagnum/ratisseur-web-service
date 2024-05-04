<?php

namespace App\Controller;

use App\Entity\ImageProfil;
use App\Repository\ImageProfilRepository;
use App\Controller\AbstractController;
use SebastianBergmann\Environment\Console;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/imageprofil")
 */
class ImageProfilController extends AbstractController
{
    /**
     * @Route("", methods={"POST"})
     */
    public function add(Request $request, ValidatorInterface $validator): Response
    {
        // $data = json_decode($request->getContent(), true);
        $files = $request->files->all();

        $user = $this->getUser();

        if (!$user) {
            $response = $this->statusCode(Response::HTTP_UNAUTHORIZED);
            // $erreursValidation[] = ['field' => 'finAt', 'message' => 'Champ invalide, précisez si la formation est finie'];
            return $this->json($response, $response['status']);
        }

        if (!isset($files)) {
            $response = $this->statusCode(Response::HTTP_BAD_REQUEST, 'L\'image est requis');
            return $this->json($response, $response['status']);
        }

        // Créer une nouvelle instance d'ImageProfil2
        $imageProfil = new ImageProfil();
        $imageProfil->setImageFile($files['imageFile']);

        // Valider l'entité ImageProfil
        if ($validationErrors = $this->validateEntity($imageProfil)) {
            return $this->json($validationErrors, $validationErrors['status']);
        }

        // Génération automatique du label
        $imageProfil->setImageName(sprintf("imageprofile_%s", uniqid()));

        // Ajouter l'utilisateur associé à l'image de profil
        $imageProfil->setUser($user);

        // Enregistrer l'entité ImageProfil dans la base de données
        $entityManager = $this->getManager();
        $entityManager->persist($imageProfil);
        $entityManager->flush();

        // Retourner une réponse JSON avec l'image de profil ajoutée et le code de statut 201 Created
        $response = $this->statusCode(Response::HTTP_CREATED);
        return $this->json($response, $response['status']);
    }
}
