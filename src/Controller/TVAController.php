<?php

namespace App\Controller;

use App\Repository\TVARepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class TVAController extends AbstractController
{
    #[Route('/tva/api', name: 'tva_api')]
    public function api(TVARepository $repo): JsonResponse
    {
        $tvas = $repo->findAll();
        $data = array_map(fn($t) => [
            'id'     => $t->getId(),
            'valeur' => $t->getValeur(),
        ], $tvas);

        return new JsonResponse($data);
    }
}