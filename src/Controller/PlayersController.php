<?php

namespace App\Controller;

use App\Data\Entity\Player;
use App\Data\Entity\Team;
use App\Form\PlayerType;
use App\Services\ApplicationServices\PlayerAS;
use App\Services\BusinessServices\PlayerBS;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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

    #[Route('/create/{team}', name: 'app_players_create', methods: ['GET', 'POST'])]
    public function create(Request $request, Team $team): Response
    {
        $player = new Player();
        $form = $this->createForm(PlayerType::class, $player);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $teamId = $team->getId();
            $this->playerBS->createPlayer(array_merge(
                $request->request->all()['player'],
                ['team' => $teamId]
            ));

            return $this->redirectToRoute('app_teams_show', ['team' => $teamId]);
        }

        return $this->renderFormView($player, $form);
    }

    #[Route('/show/{player}', name: 'app_players_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Player $player): Response
    {
        $form = $this->createForm(PlayerType::class, $player);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->playerBS->updatePlayer($player, $request->request->all()['player']);
        }

        return $this->renderFormView($player, $form);
    }

    private function renderFormView(Player $player, FormInterface $form): Response
    {
        return $this->render('players/form.twig',[
            'player' => $player,
            'form' => $form
        ]);
    }
}