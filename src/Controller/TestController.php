<?php

namespace App\Controller;

use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{

    private $trainingRepository;

    public function __construct(TrainingRepository $trainingRepository)
    {
        $this->trainingRepository = $trainingRepository;
    }

    #[Route('/test', name: 'test')]
    public function index(): Response
    {
        return $this->json([
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/users/{userId}/trainings', name: "get_user_trainings", methods: ["GET"])]
    public function getUserTrainingListAction(Request $request, int $userId): Response
    {
        $page = $request->get('page') ?? 0;
        $limit = $request->get('limit') ?? 5;
        $entities = $this->trainingRepository->findPaginatedByDate($userId, $page, $limit);
        return $this->json($entities, 200, [], ['groups' => ['default', 'training', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'training_user']]);
    }
}
