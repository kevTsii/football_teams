<?php

namespace App\Controller;

use App\Data\Constants\Context;
use App\Data\Constants\Translation;
use App\Data\DataTransferObject\PlayerDTO;
use App\Data\Entity\Player;
use App\Data\Entity\Team;
use App\Factory\TranslatorTrait;
use App\Form\PlayerType;
use App\Services\ApplicationServices\PlayerAS;
use App\Services\BusinessServices\PlayerBS;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/players')]
class PlayersController extends AbstractCommonController
{
    use TranslatorTrait;

    public function __construct(
        private readonly PlayerAS $playerAS,
        private readonly PlayerBS $playerBS,
    )
    {
    }

    #[Route('/list/{team}', name: 'app_players_index', methods: ['POST'])]
    public function index(Request $request, Team $team): Response
    {
        return $this->json(
            $this->playerAS->getDataTableResponse(
                array_merge($request->request->all(), ['team' => $team->getId()])
            )
        );
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

            $this->addFlash('success', $this->translate(
                'success.create',
                ['%context%' => 'player'],
                Translation::FLASH_MESSAGES_DOMAIN
            ));

            return $this->redirectToRoute('app_teams_show', ['team' => $teamId]);
        }

        return $this->renderFormView($player, Context::PLAYER_CONTEXT,  $form, 'players/form.html.twig');
    }

    #[Route('/show/{player}', name: 'app_players_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Player $player): Response
    {
        $form = $this->createForm(PlayerType::class, $player);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->playerBS->updatePlayer($player, $request->request->all()['player']);

            $this->addFlash('success', $this->translate(
                'success.edit',
                ['%name%' => $player->getSurname()],
                Translation::FLASH_MESSAGES_DOMAIN
            ));

            return $this->redirectToRoute('app_teams_show', ['team' => $player->getTeam()->getId()]);
        }

        return $this->renderFormView($player, Context::PLAYER_CONTEXT,  $form, 'players/form.html.twig');
    }

    #[Route('/delete/{player}', name: 'app_players_delete', methods: ['GET', 'DELETE'])]
    public function delete(Player $player): Response
    {
        try{
            $this->playerBS->deletePlayer($player);
        }catch(\Exception $e){
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute(
                'app_players_show',
                ['player' => $player->getId()]
            );
        }

        $this->addFlash('success', $this->translate(
            'success.delete',
            ['%context%' => 'player'],
            Translation::FLASH_MESSAGES_DOMAIN
        ));

        return $this->redirectToRoute('app_teams_show', [
            'team' => $player->getTeam()->getId(),
        ]);
    }

    #[Route('/by-team/{team}', name: 'app_players_get_by_teams', methods: ['GET'])]
    public function getByTeams(Request $request, Team $team): Response
    {
        return $this->json(array_map(function ($player){
            return new PlayerDTO($player);
        }, $this->playerBS->getByTeam($team)));
    }
}