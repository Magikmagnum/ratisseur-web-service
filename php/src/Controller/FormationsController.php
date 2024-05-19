<?php

// src/Controller/FormationsController.php

namespace App\Controller;

use App\Entity\Formations;
use App\Repository\FormationsRepository;
use App\Repository\FormationsListeRepository;
use App\Repository\EntreprisesRepository;
use App\Controller\AbstractController;
use App\Entity\Entreprises;
use App\Entity\FormationsListe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/formations")
 */
class FormationsController extends AbstractController
{
    /**
     * @Route("", name="formations_index", methods={"GET"})
     */
    public function index(FormationsRepository $formationsRepository): Response
    {
        $formations = $formationsRepository->findAll();
        $response = $this->statusCode(Response::HTTP_OK, $formations);
        return $this->json($response, $response["status"], [], ["groups" => "read:formation:list"]);
    }

    /**
     * @Route("", name="formations_new", methods={"POST"})
     */
    public function add(Request $request, FormationsListeRepository $formationsListeRepository, EntreprisesRepository $entreprisesRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        if ($user && isset($data['label']) && isset($data['entreprise'])) {
            $erreursValidation = [];
            $entityManager = $this->getManager();

            if (!$formationListe = $formationsListeRepository->findOneBy(['label' => $data['label']])) {
                $formationListe = new FormationsListe();
                $formationListe->setLabel($data['label']);

                if ($validationErrors = $this->validateEntity($formationListe)) {
                    return $this->json($validationErrors, $validationErrors['status']);
                }

                $entityManager->persist($formationListe);
            }

            if (!$entreprise = $entreprisesRepository->findOneBy(['label' => $data['entreprise']])) {
                $entreprise = new Entreprises();
                $entreprise->setLabel($data['entreprise']);

                if ($validationErrors = $this->validateEntity($entreprise)) {
                    return $this->json($validationErrors, $validationErrors['status']);
                }

                $entityManager->persist($entreprise);
            }

            $formation = new Formations();
            $formation->setUser($user);
            $formation->setLabel($formationListe);
            $formation->setEntreprise($entreprise);

            isset($data['debutAt']) ? $formation->setDebutAt(new \DateTimeImmutable($data['debutAt'])) : $erreursValidation[] = ['field' => 'debutAt', 'message' => 'Champ invalide'];
            isset($data['description']) && $formation->setDescription($data['description']);

            if (isset($data['finAt']) || isset($data['enCour'])) {
                isset($data['finAt']) && $formation->setFinAt(new \DateTimeImmutable($data['finAt']));
                isset($data['enCour']) && $formation->setEnCour($data['enCour']);
            } else {
                $erreursValidation[] = ['field' => 'finAt', 'message' => 'Champ invalide, précisez si la formation est finie'];
                $erreursValidation[] = ['field' => 'enCour', 'message' => 'Champ invalide, précisez si la formation est en cours'];
            }

            if ($validationErrors = $this->validateEntity($formation, $erreursValidation)) {
                return $this->json($validationErrors, $validationErrors['status']);
            }

            $entityManager->persist($formation);
            $entityManager->flush();

            $response = $this->statusCode(Response::HTTP_CREATED, $formation);
            return $this->json($response, $response["status"], [], ["groups" => "read:formation:item"]);
        }

        $response = $this->statusCode(Response::HTTP_BAD_REQUEST, 'L\'intitulé de la formation est obligatoire');
        return $this->json($response, $response['status']);
    }


    /**
     * @Route("/{id}", name="formations_show", methods={"GET"})
     */
    public function show(Formations $formation): Response
    {
        $response = $this->statusCode(Response::HTTP_OK, $formation);
        return $this->json($response, $response["status"], [], ["groups" => "read:formation:item"]);
    }

    /**
     * @Route("/{id}", name="formations_edit", methods={"PUT"})
     */
    public function edit(Request $request, Formations $formation, FormationsListeRepository $formationsListeRepository, EntreprisesRepository $entreprisesRepository): Response
    {
        if (!$this->isGranted('EDIT', $formation)) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN, 'Vous n\'avez pas la permission de modifier cette formation.');
            return $this->json($response, $response['status']);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data)) {

            $entityManager = $this->getManager();

            if (isset($data['label'])) {
                if (!$formationListe = $formationsListeRepository->findOneBy(['label' => $data['label']])) {
                    $formationListe = new FormationsListe();
                    $formationListe->setLabel($data['label']);

                    if ($validationErrors = $this->validateEntity($formationListe)) {
                        return $this->json($validationErrors, $validationErrors['status']);
                    }
                    $entityManager->persist($formationListe);
                }
                $formation->setLabel($formationListe);
            }

            if (isset($data['entreprise'])) {
                if (!$entreprise = $entreprisesRepository->findOneBy(['label' => $data['entreprise']])) {
                    $entreprise = new Entreprises();
                    $entreprise->setLabel($data['entreprise']);

                    if ($validationErrors = $this->validateEntity($entreprise)) {
                        return $this->json($validationErrors, $validationErrors['status']);
                    }
                    $entityManager->persist($entreprise);
                }
                $formation->setEntreprise($entreprise);
            }

            isset($data['description']) && $formation->setDescription($data['description']);
            isset($data['debutAt']) && $formation->setDebutAt(new \DateTimeImmutable($data['debutAt']));
            isset($data['finAt']) && $formation->setFinAt(isset($data['finAt']) ? new \DateTimeImmutable($data['finAt']) : null);
            isset($data['enCour']) && $formation->setEnCour($data['enCour']);

            $formation->setModifyAt(new \DateTimeImmutable());

            if ($validationErrors = $this->validateEntity($formation)) {
                return $this->json($validationErrors, $validationErrors['status']);
            }

            $entityManager->flush();

            $response = $this->statusCode(Response::HTTP_OK, $formation);
            return $this->json($response, $response["status"], [], ["groups" => "read:formation:item"]);
        }

        $response = $this->statusCode(Response::HTTP_BAD_REQUEST);
        return $this->json($response, $response['status']);
    }

    /**
     * @Route("/{id}", name="formations_delete", methods={"DELETE"})
     */
    public function delete(Formations $formation): Response
    {
        if (!$this->isGranted('DELETE', $formation)) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN, 'Vous n\'avez pas la permission de supprimer cette formation.');
            return $this->json($response, $response['status']);
        }

        $entityManager = $this->getManager();
        $entityManager->remove($formation);
        $entityManager->flush();

        $response = $this->statusCode(Response::HTTP_OK);
        return $this->json($response, $response['status']);
    }
}
