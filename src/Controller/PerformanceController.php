<?php

namespace App\Controller;

use App\Repository\RealisedExerciseRepository;
use App\Repository\SetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/performances')]
class PerformanceController extends AbstractController
{
    private $realisedExerciseRepository;
    private $setRepository;

    public function __construct(RealisedExerciseRepository $realisedExerciseRepository, SetRepository $setRepository)
    {
        $this->realisedExerciseRepository = $realisedExerciseRepository;
        $this->setRepository = $setRepository;
    }

    #[Route('/exercises/{referenceExerciseId}', name: 'exercise_performance_show')]
    public function showExercisePerfomance(int $referenceExerciseId): Response
    {
        $viewer = $this->getUser();
        $sets = $this->setRepository->findByReferenceExo($referenceExerciseId, $viewer ? $viewer->getId() : null);
        return $this->json($sets, 200, [], ['groups' => ['default', 'performance']]);
    }
}
