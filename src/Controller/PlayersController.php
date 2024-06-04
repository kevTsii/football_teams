<?php

namespace App\Controller;

use App\Services\BusinessServices\PlayerBS;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/players')]
class PlayersController extends AbstractController
{
    public function __construct(
        private readonly PlayerBS $playerBS,
    )
    {
    }

    #[Route('/list/{team}', name: 'app_players_index', methods: ['POST'])]
    public function index(Request $request): Response
    {
        $parameters = $request->request->all();
        dd(
            $this->playerBS->getAllPlayersPaginate(
                (int)$parameters['offset'],
                (int)$parameters['limit']
            )
        );
        return $this->playerBS->getAllPlayersPaginate((int)$parameters['offset'], (int)$parameters['limit']);
    }
}