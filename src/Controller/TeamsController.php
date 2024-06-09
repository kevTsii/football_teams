<?php

namespace App\Controller;

use App\Data\Constants\Context;
use App\Data\Entity\Team;
use App\Factory\TranslatorTrait;
use App\Form\TeamType;
use App\Services\BusinessServices\TeamBS;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/teams')]
class TeamsController extends AbstractCommonController
{
    use TranslatorTrait;

    public function __construct(
        private readonly TeamBS $teamBS,
    )
    {
    }

    #[Route('/list', name: 'app_teams_index', methods: ["GET"])]
    public function index(Request $request): Response
    {
        $page = $request->query->get('page') ?? 1;
        $limit = $request->query->get('limit') ?? 10;

        return $this->render('teams/index.html.twig', [
            'teams' => $this->teamBS->getAllTeamsPaginate((int)$page, (int)$limit)
        ]);
    }

    #[Route('/create', name: 'app_teams_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $team = new Team();

        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $team = $this->teamBS->createTeam([
                'name' => $team->getName(),
                'country' => $team->getCountry(),
                'moneyBalance' => $team->getMoneyBalance(),
            ]);

            return $this->redirectToRoute('app_teams_show', ['team' => $team->getId()]);
        }

        return $this->renderFormView($team, Context::TEAM_CONTEXT, $form, 'teams/form.html.twig');
    }

    #[Route('/show/{team}', name: 'app_teams_show', methods: ['GET'])]
    public function show(Team $team): Response
    {
        return $this->render('teams/show.html.twig',[
           'team' => $team
        ]);
    }

    #[Route('/edit/{team}', name: 'app_teams_update', methods: ['GET', 'POST'])]
    public function update(Request $request, Team $team): Response
    {
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $team = $this->teamBS->updateTeam($team, $request->request->all()['team']);

            return $this->redirectToRoute('app_teams_show', ['team' => $team->getId()]);
        }

        return $this->renderFormView($team, Context::TEAM_CONTEXT, $form, 'teams/form.html.twig');
    }

    #[Route('/delete/{team}', name: 'app_teams_delete', methods: ['GET', 'POST'])]
    public function delete(Team $team): Response
    {
        try{
            $this->teamBS->deleteTeam($team);
            $this->addFlash('success', 'message');

            return $this->redirectToRoute('app_teams_show', ['team' => $team->getId()]);
        }catch(\Exception $e){
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('app_teams_show', ['team' => $team->getId()]);
        }
    }
}