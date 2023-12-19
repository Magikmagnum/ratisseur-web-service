<?php

namespace App\Controller;


use App\Entity\Brand;
use App\Entity\Produit;
use App\Entity\Category;
use App\Entity\Characteristic;

use App\Repository\ProduitRepository;
use App\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class CroquetteController extends AbstractController
{
    #[Route('/croquette', name: 'croquette_list', methods: "GET"),]
    public function list(ProduitRepository $produitRepository): JsonResponse
    {
        $response = $this->statusCode(Response::HTTP_OK, $produitRepository->findAll());
        return $this->json($response, $response["status"], [], ["groups" => "produit:list"]);
    }


    #[Route('/croquette/{id}', name: 'croquette_show', methods: "GET"),]
    public function showOne($id, ProduitRepository $produitRepository): JsonResponse
    {
        if ($produits = $produitRepository->findOneBy(['id' => $id])) {
            $response = $this->statusCode(Response::HTTP_OK, $produits);
            return $this->json($response, $response["status"], [], ["groups" => "produit:list"]);
        }
        $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        return $this->json($response, $response["status"]);
    }

    #[Route('/croquette_by_brand/{brand}', name: 'marque_show', methods: "GET"),]
    public function showByBrand($brand, ProduitRepository $produitRepository): JsonResponse
    {
        $products = $produitRepository->findDistinc($brand);
        $response = $this->statusCode(Response::HTTP_OK, $products);
        return $this->json($response, $response["status"], [], ["groups" => "brand:list"]);
    }

    #[Route('/croquette', name: 'croquette_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Extract the data from the JSON payload
        $name = $data['name'];
        $url = $data['url'];
        $urlimage = $data['urlimage'];
        $validate = $data['validate'];
        $sterilise = $data['sterilise'];
        $productId = $data['productId'];
        $brandName = $data['brand']['name'];
        $typePet = $data['categories'][0]['typePet'];
        $cendres = $data['characteristic']['cendres'];
        $eau = $data['characteristic']['eau'];
        $fibre = $data['characteristic']['fibre'];
        $glucide = $data['characteristic']['glucide'];
        $lipide = $data['characteristic']['lipide'];
        $proteine = $data['characteristic']['proteine'];

        // Create the new product entity and set its properties
        $product = new Produit();
        $product->setName($name);
        $product->setUrl($url);
        $product->setUrlImage($urlimage);
        $product->setValidate($validate);
        $product->setSterilise($sterilise);
        $product->setCreatedAt(new \DateTime()); // Set the created_at value


        // Retrieve the brand entity by name or create a new one
        $brand = $entityManager->getRepository(Brand::class)->findOneBy(['name' => $brandName]);
        if (!$brand) {
            $brand = new Brand();
            $brand->setName($brandName);
            $brand->setCreatedAt(new \DateTime()); // Set the created_at value

            // Save the brand to the database
            $entityManager->persist($brand);
        }
        $product->setBrand($brand);

        // Create the category entity and set its properties
        $category = new Category();
        $category->setTypePet($typePet);
        $category->setCreatedAt(new \DateTime()); // Set the created_at value

        // Save the category to the database
        $entityManager->persist($category);

        $product->addCategory($category);

        // Create the characteristic entity and set its properties
        $characteristic = new Characteristic();
        $characteristic->setCendres($cendres);
        $characteristic->setEau($eau);
        $characteristic->setFibre($fibre);
        $characteristic->setGlucide($glucide);
        $characteristic->setLipide($lipide);
        $characteristic->setProteine($proteine);
        $characteristic->setProduit($product);


        // Save the characteristic to the database
        $entityManager->persist($characteristic);


        $product->setCharacteristic($characteristic);

        // Save the product to the database
        $entityManager->persist($product);
        $entityManager->flush();

        $response = $this->statusCode(Response::HTTP_CREATED, $product);
        return $this->json($response, $response["status"], [], ["groups" => "produit:list"]);
    }

    #[Route('/croquette/{productId}', name: 'croquette_edit', methods: ['PUT'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, int $productId): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Retrieve the existing product entity by ID
        $product = $entityManager->getRepository(Produit::class)->find($productId);

        // If the product doesn't exist, return a not found response
        if (!$product) {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND, "Product not found.");
            return $this->json($response, $response["status"]);
        }

        // Extract the data from the JSON payload (handle optional fields with null coalescing operator)
        $name = $data['name'] ?? $product->getName();
        $url = $data['url'] ?? $product->getUrl();
        $urlimage = $data['urlimage'] ?? $product->getUrlImage();
        $validate = $data['validate'] ?? $product->isValidate();
        $sterilise = $data['sterilise'] ?? $product->isSterilise();
        $brandName = $data['brand']['name'] ?? $product->getBrand()->getName();
        $typePet = $data['categories'][0]['typePet'] ?? $product->getCategories()->first()->getTypePet();
        $cendres = $data['characteristic']['cendres'] ?? $product->getCharacteristic()->getCendres();
        $eau = $data['characteristic']['eau'] ?? $product->getCharacteristic()->getEau();
        $fibre = $data['characteristic']['fibre'] ?? $product->getCharacteristic()->getFibre();
        $glucide = $data['characteristic']['glucide'] ?? $product->getCharacteristic()->getGlucide();
        $lipide = $data['characteristic']['lipide'] ?? $product->getCharacteristic()->getLipide();
        $proteine = $data['characteristic']['proteine'] ?? $product->getCharacteristic()->getProteine();

        // Set the updated properties of the product entity
        $product->setName($name);
        $product->setUrl($url);
        $product->setUrlImage($urlimage);
        $product->setValidate($validate);
        $product->setSterilise($sterilise);
        $product->setModifyAt(new \DateTime()); // Set the updated_at value

        // Retrieve the brand entity
        $product->getBrand()->setName($brandName);
        $product->getBrand()->setModifyAt(new \DateTime()); // Set the created_at value

        // Retrieve the existing category entity
        $product->getCategories()[0]->setTypePet($typePet);
        $product->getCategories()[0]->setModitfyAt(new \DateTime());

        // Update the characteristic entity
        $product->getCharacteristic()->setCendres($cendres);
        $product->getCharacteristic()->setEau($eau);
        $product->getCharacteristic()->setFibre($fibre);
        $product->getCharacteristic()->setGlucide($glucide);
        $product->getCharacteristic()->setLipide($lipide);
        $product->getCharacteristic()->setProteine($proteine);

        // Save the entities to the database
        $entityManager->persist($product);
        $entityManager->flush();

        $response = $this->statusCode(Response::HTTP_OK, $product);
        return $this->json($response, $response["status"], [], ["groups" => "produit:list"]);
    }

    #[Route('/croquette/{id}', name: 'produit_delete', methods: ['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $entityManager, ProduitRepository $produitRepository): JsonResponse
    {
        // Récupérer l'ID du produit à supprimer depuis la requête
        $produitId = $request->attributes->get('id');

        // Chercher le produit dans la base de données
        $produit = $produitRepository->find($produitId);

        // Vérifier si le produit existe
        if (!$produit) {
            // Retourner une réponse d'erreur si le produit n'est pas trouvé
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
            return $this->json($response, $response["status"]);
        }

        // Supprimer le produit de la base de données
        $entityManager->remove($produit);
        $entityManager->flush();


        // Retourner une réponse de succès
        $response = $this->statusCode(Response::HTTP_OK);
        return $this->json($response, $response["status"]);
    }


    #[Route('/croquette/motion', name: 'croquette_motion', methods: ['POST'])]
    public function motion(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Extract the data from the JSON payload
        $name = $data['name'];
        $validate = $data['validate'];
        $brandName = $data['brand']['name'];
        $typePet = $data['categories'][0]['typePet'];


        // Create the new product entity and set its properties
        $product = new Produit();
        $product->setName($name);
        $product->setValidate($validate);
        $product->setCreatedAt(new \DateTime()); // Set the created_at value


        // Retrieve the brand entity by name or create a new one
        $brand = $entityManager->getRepository(Brand::class)->findOneBy(['name' => $brandName]);
        if (!$brand) {
            $brand = new Brand();
            $brand->setName($brandName);
            $brand->setValidate(0);
            $brand->setCreatedAt(new \DateTime()); // Set the created_at value

            // Save the brand to the database
            $entityManager->persist($brand);
        }
        $product->setBrand($brand);

        // Create the category entity and set its properties
        $category = new Category();
        $category->setTypePet($typePet);
        $category->setCreatedAt(new \DateTime()); // Set the created_at value

        // Save the category to the database
        $entityManager->persist($category);

        $product->addCategory($category);

        // Save the product to the database
        $entityManager->persist($product);
        $entityManager->flush();

        $response = $this->statusCode(Response::HTTP_CREATED, $product);
        return $this->json($response, $response["status"], [], ["groups" => "produit:list"]);
    }
}
