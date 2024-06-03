<?php

namespace App\Controller;

use App\Data\Entity\Team;
use App\Form\TeamType;
use App\Services\BusinessServices\TeamBS;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/teams')]
class TeamsController extends AbstractController
{

    public function __construct(
        private readonly TeamBS $teamBS,
    )
    {
    }

    #[Route('/', name: 'app_teams_index', methods: ["GET"])]
    public function index(Request $request): Response
    {
        return $this->render('teams/index.html.twig');
    }

    #[Route('/create', name: 'app_teams_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $team = new Team();

        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->teamBS->createTeam([
                'name' => $team->getName(),
                'country' => $team->getCountry(),
                'moneyBalance' => $team->getMoneyBalance(),
            ]);
        }

        return $this->render('teams/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}