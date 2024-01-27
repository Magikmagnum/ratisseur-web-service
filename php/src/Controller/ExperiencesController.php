<?php

// src/Controller/ExperiencesController.php

namespace App\Controller;

use App\Entity\Experiences;
use App\Repository\ExperiencesRepository;
use App\Repository\ExperiencesListeRepository;
use App\Repository\EntreprisesRepository;
use App\Controller\AbstractController;
use App\Entity\Entreprises;
use App\Entity\ExperiencesListe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/experiences")
 */
class ExperiencesController extends AbstractController
{
    /**
     * @Route("", name="experiences_index", methods={"GET"})
     */
    public function index(ExperiencesRepository $experiencesRepository): Response
    {
        $experiences = $experiencesRepository->findAll();
        $response = $this->statusCode(Response::HTTP_OK, $experiences);
        return $this->json($response, $response["status"], [], ["groups" => "read:experience:list"]);
    }


    /**
     * @Route("", name="experiences_new", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(Request $request, ExperiencesListeRepository $experiencesListeRepository, EntreprisesRepository $entreprisesRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();
        // Vérifier l'existence des données nécessaires
        if ($user && isset($data['label']) && isset($data['entreprise'])) {

            $erreursValidation = [];
            $entityManager = $this->getManager();

            // Rechercher une ExperiencesListe existante avec le même label
            if (!$experienceListe = $experiencesListeRepository->findOneBy(['label' => $data['label']])) {
                // Créer une instance de ExperiencesListe
                $experienceListe = new ExperiencesListe();
                $experienceListe->setLabel($data['label']);

                // Si des erreurs de validation sont trouvées, renvoyer une réponse avec les erreurs
                if ($validationErrors = $this->validateEntity($experienceListe)) {
                    return $this->json($validationErrors, $validationErrors['status']);
                }

                // Persister l'entité ExperiencesListe
                $entityManager->persist($experienceListe);
            }

            // Rechercher une ExperiencesListe existante avec le même label
            if (!$entreprises = $entreprisesRepository->findOneBy(['label' => $data['entreprise']])) {
                // Créer une instance de Entreprises
                $entreprises = new Entreprises();
                $entreprises->setLabel($data['entreprise']);

                // Si des erreurs de validation sont trouvées, renvoyer une réponse avec les erreurs
                if ($validationErrors = $this->validateEntity($entreprises)) {
                    return $this->json($validationErrors, $validationErrors['status']);
                }

                $entityManager->persist($entreprises);
            }

            // Compléter les détails de l'expérience
            $experience = new Experiences();
            $experience->setUser($user);
            $experience->setLabel($experienceListe);
            $experience->setEntreprise($entreprises);

            isset($data['debutAt']) ? $experience->setDebutAt(new \DateTimeImmutable($data['debutAt'])) : $erreursValidation[] = ['field' => 'debutAt', 'message' => 'Champ invalide'];
            isset($data['description']) && $experience->setDescription($data['description']);

            if (isset($data['finAt']) || isset($data['enCour'])) {
                isset($data['finAt']) && $experience->setFinAt(new \DateTimeImmutable($data['finAt']));
                isset($data['enCour']) && $experience->setEnCour($data['enCour']);
            } else {
                $erreursValidation[] = ['field' => 'finAt', 'message' => 'Champ invalide, précisez si l\'expérience est finie'];
                $erreursValidation[] = ['field' => 'enCour', 'message' => 'Champ invalide, précisez si l\'expérience est en cours'];
            }

            // Si des erreurs de validation sont trouvées, renvoyer une réponse avec les erreurs
            if ($validationErrors = $this->validateEntity($experience, $erreursValidation)) {
                return $this->json($validationErrors, $validationErrors['status']);
            }

            $entityManager->persist($experience);
            $entityManager->flush();

            $response = $this->statusCode(Response::HTTP_CREATED, $experience);
            return $this->json($response, $response["status"], [], ["groups" => "read:experience:item"]);
        }

        $response = $this->statusCode(Response::HTTP_BAD_REQUEST, 'L\'intitulé de l\'expérience est obligatoire');
        return $this->json($response, $response['status']);
    }


    /**
     * @Route("/{id}", name="experiences_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Experiences $experience): Response
    {
        $response = $this->statusCode(Response::HTTP_OK, $experience);
        return $this->json($response, $response["status"], [], ["groups" => "read:experience:item"]);
    }

    /**
     * @Route("/{id}", name="experiences_edit", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Experiences $experience, ExperiencesListeRepository $experiencesListeRepository, EntreprisesRepository $entreprisesRepository): Response
    {
        // Ici, nous vérifions si l'utilisateur actuel est autorisé à modifier cette expérience.
        if (!$this->isGranted('EDIT', $experience)) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN, 'Vous n\'avez pas la permission de modifier cette expérience.');
            return $this->json($response, $response['status']);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data)) {
            $entityManager = $this->getManager();

            if (isset($data['label'])) {
                // Rechercher une ExperiencesListe existante avec le même label
                if (!$experienceListe = $experiencesListeRepository->findOneBy(['label' => $data['label']])) {
                    // Créer une instance de ExperiencesListe
                    $experienceListe = new ExperiencesListe();
                    $experienceListe->setLabel($data['label']);

                    // Si des erreurs de validation sont trouvées, renvoyer une réponse avec les erreurs
                    if ($validationErrors = $this->validateEntity($experienceListe)) {
                        return $this->json($validationErrors, $validationErrors['status']);
                    }
                    // Persister l'entité ExperiencesListe
                    $entityManager->persist($experienceListe);
                }
                $experience->setLabel($experienceListe);
            }

            if (isset($data['entreprise'])) {
                // Rechercher une ExperiencesListe existante avec le même label
                if (!$entreprises = $entreprisesRepository->findOneBy(['label' => $data['entreprise']])) {
                    // Créer une instance de Entreprises
                    $entreprises = new Entreprises();
                    $entreprises->setLabel($data['entreprise']);

                    // Si des erreurs de validation sont trouvées, renvoyer une réponse avec les erreurs
                    if ($validationErrors = $this->validateEntity($entreprises)) {
                        return $this->json($validationErrors, $validationErrors['status']);
                    }
                    $entityManager->persist($entreprises);
                }
                $experience->setEntreprise($entreprises);
            }
            isset($data['description']) && $experience->setDescription($data['description']);
            isset($data['debutAt']) && $experience->setDebutAt(new \DateTimeImmutable($data['debutAt']));
            isset($data['finAt']) && $experience->setFinAt(isset($data['finAt']) ? new \DateTimeImmutable($data['finAt']) : null);
            isset($data['enCour']) && $experience->setEnCour($data['enCour']);

            $experience->setModifyAt(new \DateTimeImmutable());

            if ($validationErrors = $this->validateEntity($experience)) {
                return $this->json($validationErrors, $validationErrors['status']);
            }

            $entityManager->flush();

            $response = $this->statusCode(Response::HTTP_OK, $experience);
            return $this->json($response, $response["status"], [], ["groups" => "read:experience:item"]);
        }

        $response = $this->statusCode(Response::HTTP_BAD_REQUEST);
        return $this->json($response, $response['status']);
    }

    /**
     * @Route("/{id}", name="experiences_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Experiences $experience): Response
    {
        // Ici, nous vérifions si l'utilisateur actuel est autorisé à supprimer cette expérience.
        if (!$this->isGranted('DELETE', $experience)) {
            $response = $this->statusCode(Response::HTTP_FORBIDDEN, 'Vous n\'avez pas la permission de supprimer cette expérience.');
            return $this->json($response, $response['status']);
        }

        $entityManager = $this->getManager();
        $entityManager->remove($experience);
        $entityManager->flush();

        $response = $this->statusCode(Response::HTTP_OK);
        return $this->json($response, $response['status']);
    }
}
