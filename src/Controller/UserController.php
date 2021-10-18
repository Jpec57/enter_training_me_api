<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrainingRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Services\MailerService;

#[Route('/api/users')]
class UserController extends AbstractController
{
    private $trainingRepository;
    private $mailerService;

    public function __construct(TrainingRepository $trainingRepository, MailerService $mailerService)
    {
        $this->trainingRepository = $trainingRepository;
        $this->mailerService = $mailerService;
    }


    #[Route('/feed', name: "user_feed", methods: ["GET"])]
    public function officialList(Request $request): Response
    {
        dump($this->getUser());
        $page = $request->get('page') ?? 0;
        $limit = $request->get('limit') ?? 10;
        $entities = $this->trainingRepository->findForFeed($page, $limit);
        return $this->json($entities, 200, [], ['groups' => ['default', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'exercise_cycle_exercise', 'training_exercise_cycle', 'training_user', 'exercise_cycle_exercise']]);
    }
}
