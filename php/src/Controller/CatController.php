<?php

namespace App\Controller;

use App\Entity\Cat;
use App\Repository\CatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Controller\AbstractController;

class CatController extends AbstractController
{
    #[Route('/cats', name: 'cat_list', methods: 'GET')]
    public function list(CatRepository $catRepository): JsonResponse
    {
        $cats = $catRepository->findAllValidated();
        $response = $this->statusCode(Response::HTTP_OK, $cats);
        return $this->json($response, $response["status"]);
    }

    #[Route('/cats/{id}', name: 'cat_show', methods: 'GET')]
    public function showOne($id, CatRepository $catRepository): JsonResponse
    {
        $cat = $catRepository->find($id);

        if (!$cat) {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
            return $this->json($response, $response["status"]);
        }

        $response = $this->statusCode(Response::HTTP_OK, $cat);
        return $this->json($response, $response["status"]);
    }

    #[Route('/cats', name: 'cat_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validate and handle data from the request
        // Assuming the request should include a "name" field
        $name = $data['name'];

        // Create a new Cat entity and set its properties
        $cat = new Cat();
        $cat->setName($name);
        $cat->setValidate(0);
        $cat->setCreatedAt(new \DateTimeImmutable());

        // Persist the entity
        $entityManager->persist($cat);
        $entityManager->flush();

        $response = $this->statusCode(Response::HTTP_CREATED, $cat);
        return $this->json($response, $response["status"]);
    }

    #[Route('/cats/{id}', name: 'cat_edit', methods: ['PUT'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $cat = $entityManager->getRepository(Cat::class)->find($id);

        if (!$cat) {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
            return $this->json($response, $response["status"]);
        }

        // Update the cat's properties if provided in the request
        if (isset($data['name'])) {
            $cat->setName($data['name']);
            // You can add more fields to update here
        }

        // Persist the changes
        $entityManager->flush();

        $response = $this->statusCode(Response::HTTP_OK, $cat);
        return $this->json($response, $response["status"]);
    }

    #[Route('/cats/{id}', name: 'cat_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $cat = $entityManager->getRepository(Cat::class)->find($id);

        if (!$cat) {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
            return $this->json($response, $response["status"]);
        }

        // Remove the cat entity
        $entityManager->remove($cat);
        $entityManager->flush();

        $response = $this->statusCode(Response::HTTP_OK);
        return $this->json($response, $response["status"]);
    }
}
