<?php

namespace App\Controller;

use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/trainings')]
class TrainingController extends AbstractController
{
    private $trainingRepository;

    public function __construct(TrainingRepository $trainingRepository)
    {
        $this->trainingRepository = $trainingRepository;
    }

    #[Route('/', name: "training_list", methods: ["GET"])]
    public function list(): Response
    {
        $entities = $this->trainingRepository->findAll();
        return $this->json($entities, 200, [], ['groups' => ['default', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'exercise_cycle_exercise', 'training_exercise_cycle', 'training_user', 'exercise_cycle_exercise']]);
    }


    #[Route('/summary', name: "training_summary", methods: ["GET"])]
    public function summary(): Response
    {
        $entities = $this->trainingRepository->findAll();
        return $this->json($entities, 200, [], ['groups' => ['summary']]);
    }
}
