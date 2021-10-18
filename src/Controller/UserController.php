<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrainingRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Services\MailerService;

#[Route('/api/users')]
class UserController extends AbstractController
{
    private $trainingRepository;
    private $userRepository;
    private $mailerService;

    public function __construct(UserRepository $userRepository, TrainingRepository $trainingRepository, MailerService $mailerService)
    {
        $this->trainingRepository = $trainingRepository;
        $this->userRepository = $userRepository;
        $this->mailerService = $mailerService;
    }


    #[Route('/feed', name: "user_feed", methods: ["GET"])]
    public function officialList(Request $request): Response
    {
        $page = $request->get('page') ?? 0;
        $limit = $request->get('limit') ?? 10;
        $entities = $this->trainingRepository->findForFeed($page, $limit);
        return $this->json($entities, 200, [], ['groups' => ['default', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'exercise_cycle_exercise', 'training_exercise_cycle', 'training_user', 'exercise_cycle_exercise']]);
    }

    #[Route('/token/{token}', name: "user_by_token_action", methods: ["GET"])]
    public function getByTokenAction(Request $request, string $token): Response
    {
        $user = $this->userRepository->findOneBy(['apiTokens' => $token]);
        return $this->json($user, 200, [], ['groups' => ['default']]);
    }
}
