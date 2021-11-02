<?php

namespace App\Controller;

use App\Repository\ApiTokenRepository;
use App\Repository\ExerciseReferenceRepository;
use App\Repository\RealisedExerciseRepository;
use App\Repository\SetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrainingRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Services\MailerService;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/users')]
class UserController extends AbstractController
{
    private $trainingRepository;
    private $realisedExerciseRepository;
    private $referenceExoRepo;
    private $apiTokenRepository;
    private $mailerService;

    public function __construct(ExerciseReferenceRepository $referenceExoRepo, RealisedExerciseRepository $realisedExerciseRepository, ApiTokenRepository $apiTokenRepository, UserRepository $userRepository, TrainingRepository $trainingRepository, MailerService $mailerService)
    {
        $this->trainingRepository = $trainingRepository;
        $this->userRepository = $userRepository;
        $this->apiTokenRepository = $apiTokenRepository;
        $this->mailerService = $mailerService;
        $this->realisedExerciseRepository = $realisedExerciseRepository;
        $this->referenceExoRepo = $referenceExoRepo;
    }



    #[Route('/realised_exercises', name: "user_realised_exo_list", methods: ["GET"])]
    public function getRealisedExercisesByUserList(Request $request): Response
    {
        $viewer = $this->getUser();
        if (!$viewer) {
            return $this->json([], 200);
        }

        $ids = $this->realisedExerciseRepository->findReferenceExerciseIdsByUser($viewer->getId());
        $entities = [];
        if ($ids && !empty($ids)) {
            $entities = $this->referenceExoRepo->findBy(["id" => $ids]);
        }
        return $this->json($entities, 200, [], ['groups' => ['default']]);
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


    #[Route('/infos/{id}', name: "user_profile_info", methods: ["GET"], requirements: [])]
    public function getUserProfileInfo(SerializerInterface $serializer, ?int $id = null): Response
    {
        $viewer = $this->getUser();
        $user = ($id != null) ? $this->userRepository->find($id) : $viewer;
        if (!$user) {
            return $this->json([], 404);
        }
        if (!$viewer || $viewer->getId() != $user->getId()) {
            return $this->json([], 403);
        }

        $trainingCount = $this->trainingRepository->count(['author' => $user]);
        $trainings = $this->trainingRepository->findBy(['author' => $user], ['createdAt' => 'DESC'], 5, 0);
        $res = [];
        $res['user'] = $serializer->normalize($user, null, ['groups' => ['default']]);
        $res["trainingCount"] = $trainingCount;
        $res["lastTrainings"] = $serializer->normalize($trainings, null, ['groups' => ['default']]);
        return $this->json($res);
    }
}
