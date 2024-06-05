<?php

namespace App\Controller;

use App\Services\ApplicationServices\PlayerAS;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/players')]
class PlayersController extends AbstractController
{
    public function __construct(
        private readonly PlayerAS $playerAS,
    )
    {
    }

    #[Route('/list/{team}', name: 'app_players_index', methods: ['POST'])]
    public function index(Request $request): Response
    {
        return $this->json($this->playerAS->getDataTableResponse($request->request->all()));
    }
}