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
        // dump($entities);
        // foreach ($entities as $entity) {
        //     $cycles = $entity->getCycles();
        //     foreach ($cycles as $cycle) {
        //         dump("-----CYCLES----------");
        //         $exercises = $cycle->getExercises();
        //         foreach ($exercises as $exercise) {
        //             dump("-----EXERCISE----------");
        //             $sets = $exercise->getSets();
        //             foreach ($sets as $set) {
        //                 dump($set);
        //             }
        //         }
        //         // dump($cycle);
        //     }
        //     // dump();
        // }
        return $this->json($entities, 200, [], ['groups' => ['default', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'exercise_cycle_exercise', 'training_exercise_cycle', 'training_user', 'exercise_cycle_exercise']]);
    }
}
