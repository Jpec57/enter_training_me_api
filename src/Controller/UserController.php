<?php

namespace App\Controller;

use App\Repository\ApiTokenRepository;
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
    private $apiTokenRepository;
    private $mailerService;

    public function __construct(ApiTokenRepository $apiTokenRepository, UserRepository $userRepository, TrainingRepository $trainingRepository, MailerService $mailerService)
    {
        $this->trainingRepository = $trainingRepository;
        $this->userRepository = $userRepository;
        $this->apiTokenRepository = $apiTokenRepository;
        $this->mailerService = $mailerService;
    }


    #[Route('/feed', name: "user_feed", methods: ["GET"])]
    public function officialList(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->json([], 200);
        }
        $page = $request->get('page') ?? 0;
        $limit = $request->get('limit') ?? 5;
        $entities = $this->trainingRepository->findForFeed($page, $limit);
        return $this->json($entities, 200, [], ['groups' => ['default', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'exercise_cycle_exercise', 'training_exercise_cycle', 'training_user', 'exercise_cycle_exercise']]);
    }

    #[Route('/token/{token}', name: "user_by_token_action", methods: ["GET"])]
    public function getByTokenAction(Request $request, string $token): Response
    {
        $token = $this->apiTokenRepository->findOneBy(['token' => $token]);
        if (!$token) {
            return $this->json(["message" => "Invalid token."], 400);
        }
        return $this->json($token->getAssociatedUser(), 200, [], ['groups' => ['default']]);
    }
}
