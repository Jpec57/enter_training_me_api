<?php

namespace App\Controller;

use App\Repository\FitnessTeamRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/fitness_teams')]
class FitnessTeamController extends AbstractController
{
    private FitnessTeamRepository $fitnessTeamRepository;
    private UserRepository $userRepository;

    public function __construct(FitnessTeamRepository $fitnessTeamRepository, UserRepository $userRepository)
    {
        $this->fitnessTeamRepository = $fitnessTeamRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('/{id}/join', name: 'fitness_team_join')]
    public function joinTeamAction(int $id): Response
    {
        $viewer = $this->getUser();
        if (!$viewer) {
            return $this->json([], 403);
        }
        $team = $this->fitnessTeamRepository->find($id);
        if (!$team) {
            return $this->json([], 404);
        }
        //TODO 
        return $this->json([], 200);
    }

    #[Route('/{id}/leave', name: 'fitness_team_leave')]
    public function leaveTeamAction(int $id): Response
    {
        $viewer = $this->getUser();
        if (!$viewer) {
            return $this->json([], 403);
        }
        $team = $this->fitnessTeamRepository->find($id);
        if (!$team) {
            return $this->json([], 404);
        }

        //TODO 
        return $this->json([], 200);
    }

    #[Route('/{teamId}/exclude/{userId}', name: 'fitness_team_exclude_user')]
    public function excludeFromTeamAction(int $teamId, int $userId): Response
    {
        $viewer = $this->getUser();
        if (!$viewer) {
            return $this->json([], 403);
        }
        $team = $this->fitnessTeamRepository->find($teamId);
        if (!$team) {
            return $this->json([], 404);
        }

        $user = $this->userRepository->find($userId);
        //TODO 
        return $this->json([], 200);
    }
}
