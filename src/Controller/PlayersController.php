<?php

namespace App\Controller;

use App\Data\Entity\Player;
use App\Form\PlayerType;
use App\Services\ApplicationServices\PlayerAS;
use App\Services\BusinessServices\PlayerBS;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/players')]
class PlayersController extends AbstractController
{
    public function __construct(
        private readonly PlayerAS $playerAS,
        private readonly PlayerBS $playerBS,
    )
    {
    }

    #[Route('/list/{team}', name: 'app_players_index', methods: ['POST'])]
    public function index(Request $request): Response
    {
        return $this->json($this->playerAS->getDataTableResponse($request->request->all()));
    }

    #[Route('/show/{player}', name: 'app_players_show', methods: ['GET'])]
    public function show(Request $request, Player $player): Response
    {
        $form = $this->createForm(PlayerType::class, $player);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->playerBS->updatePlayer($player, $request->request->all());
        }

        return $this->render('players/show.html.twig',[
            'player' => $player,
            'form' => $form
        ]);
    }
}