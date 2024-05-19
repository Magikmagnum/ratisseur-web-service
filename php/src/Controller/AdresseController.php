<?php

namespace App\Controller;


use App\DTO\AdresseDTO;
use App\Controller\AbstractController;
use App\Services\AdresseServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class AdresseController extends AbstractController
{
    #[Route('/adresse', name: 'add_adresse', methods: ['POST'])]
    public function new(Request $request, AdresseServices $adresseServices): Response
    {
        return  $adresseServices->new($request);
    }
}
