<?php

namespace App\Controller;

use App\Repository\FitnessTeamRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rankings')]
class RankingController extends AbstractController
{
    private FitnessTeamRepository $fitnessTeamRepository;
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository, FitnessTeamRepository $fitnessTeamRepository)
    {
        $this->fitnessTeamRepository = $fitnessTeamRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('/users', name: 'user_ranking')]
    public function getUserRankingAction(Request $request): Response
    {
        $rankingType = $request->get('rankingType') ?? 'global';
        $users = $this->userRepository->findOrderedByRanking($rankingType);
        return $this->json($users, 200);
    }

    #[Route('/teams', name: 'team_ranking')]
    public function getTeamRankingAction(Request $request): Response
    {
        $teams = $this->fitnessTeamRepository->findOrderedByRanking();
        return $this->json($teams, 200, [], ['groups' => ['default', 'performance']]);
    }
}
