<?php

namespace App\Services;

use App\Entity\Pays;
use App\Entity\Ville;
use App\DTO\AdresseDTO;
use App\Entity\Adresse;
use App\Services\DTOServices;
use App\Repository\PaysRepository;
use App\Repository\VilleRepository;
use App\Repository\AdresseRepository;
use App\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdresseServices extends AbstractController
{
    private $adresseRepository;
    private $villeRepository;
    private $paysRepository;
    private $em;

    public function __construct(
        AdresseRepository $adresseRepository,
        VilleRepository $villeRepository,
        PaysRepository $paysRepository,
        EntityManagerInterface $em
    ) {
        $this->adresseRepository = $adresseRepository;
        $this->villeRepository = $villeRepository;
        $this->paysRepository = $paysRepository;
        $this->em = $em;
    }

    public function new(Request $request): Response
    {
        if ($user = $this->getUser()) {
            $data = json_decode($request->getContent(), true);
            $adresseDto = DTOServices::initializer(AdresseDTO::class, $data['rue'], $data['appartement'], $data['codePostal'], $data['ville'], $data['pays']);

            // Récupérer le pays spécifié dans les données
            $pays = $this->paysRepository->findOneBy(['label' => $adresseDto->pays]);

            // Si le pays n'existe pas, créez une nouvelle instance
            if (!$pays) {
                $pays = new Pays();
                $pays->setLabel($adresseDto->pays);
                // Sauvegardez le nouveau pays dans la base de données
                $this->em->persist($pays);
            }

            // Récupérer la ville spécifiée dans les données
            $ville = $this->villeRepository->findOneBy(['label' => $adresseDto->ville, 'pays' => $pays]);

            // Si la ville n'existe pas, créez une nouvelle instance
            if (!$ville) {
                $ville = new Ville();
                $ville->setLabel($adresseDto->ville);
                $ville->setCodePostal($adresseDto->codePostal);
                $ville->setPays($pays);
                // Sauvegardez la nouvelle ville dans la base de données
                $this->em->persist($ville);
            }

            // Rechercher une adresse où la rue et l'appartement correspondent aux données
            $adresse = $this->adresseRepository->findOneBy([
                'rue' => $adresseDto->rue,
                'appartement' => $adresseDto->appartement,
                'villes' => $ville
            ]);

            // Si l'adresse n'existe pas, créez une nouvelle instance
            if (!$adresse) {
                $adresse = new Adresse();
                $adresse->setRue($adresseDto->rue);
                $adresse->setAppartement($adresseDto->appartement);
                // Associez l'adresse à la ville appropriée
                $adresse->setVilles($ville);
                // Sauvegardez la nouvelle adresse dans la base de données
                $this->em->persist($adresse);
            }

            $user->setAdresse($adresse);

            // Exécutez toutes les requêtes SQL en attente
            $this->em->flush();
            $response = $this->statusCode(Response::HTTP_OK, $adresse);
            return $this->json($response, $response["status"], [], ["groups" => "read:adresse:item"]);
        }

        $response = $this->statusCode(Response::HTTP_FORBIDDEN);
        return $this->json($response, $response['status']);
    }
}
