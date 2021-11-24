<?php

namespace App\Controller;

use App\Form\FitnessProfileType;
use App\Form\UserType;
use App\Repository\ApiTokenRepository;
use App\Repository\ExerciseReferenceRepository;
use App\Repository\RealisedExerciseRepository;
use App\Repository\SetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrainingRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use App\Services\MailerService;
use App\Traits\FormTrait;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/api/users')]
class UserController extends AbstractController
{
    use FormTrait;

    private $trainingRepository;
    private $realisedExerciseRepository;
    private $referenceExoRepo;
    private $apiTokenRepository;
    private $mailerService;
    private $fileUploader;

    public function __construct(FileUploader $fileUploader, ExerciseReferenceRepository $referenceExoRepo, RealisedExerciseRepository $realisedExerciseRepository, ApiTokenRepository $apiTokenRepository, UserRepository $userRepository, TrainingRepository $trainingRepository, MailerService $mailerService)
    {
        $this->trainingRepository = $trainingRepository;
        $this->userRepository = $userRepository;
        $this->apiTokenRepository = $apiTokenRepository;
        $this->mailerService = $mailerService;
        $this->realisedExerciseRepository = $realisedExerciseRepository;
        $this->referenceExoRepo = $referenceExoRepo;
        $this->fileUploader = $fileUploader;
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


    #[Route('/update-user', name: "user_update", methods: ["PATCH", "PUT"])]
    public function updateAction(Request $request): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        if (!$viewer) {
            return $this->json([], 403);
        }
        $data = json_decode($request->getContent(), true);
        $clearMissing = $request->getMethod() != 'PATCH';

        $form = $this->createForm(UserType::class, $viewer);
        $form->submit($data, $clearMissing);
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($viewer);
            $em->flush();
            return $this->json($viewer, 200, [], ['groups' => ['default']]);
        }
        $errors = $this->getErrorsFromForm($form);
        return $this->json(["errors" => $errors], 400);
    }



    #[Route('/update', name: "user_update_fitness_profile", methods: ["PATCH", "PUT"])]
    public function updateFitnessProfileAction(Request $request): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        if (!$viewer) {
            return $this->json([], 403);
        }
        $data = json_decode($request->getContent(), true);
        $clearMissing = $request->getMethod() != 'PATCH';
        $form = $this->createForm(FitnessProfileType::class, $viewer->getFitnessProfile());
        $form->submit($data, $clearMissing);
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($viewer);
            $em->flush();
            return $this->json($viewer, 200, [], ['groups' => ['default']]);
        }
        $errors = $this->getErrorsFromForm($form);
        return $this->json(["errors" => $errors], 400);
    }


    #[Route('/profile_pic', name: "user_profile_pic_change", methods: ["POST"])]
    public function profilePicChangeAction(Request $request): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        if (!$viewer) {
            return $this->json([], 403);
        }
        /** @var UploadedFile $uploadedFile */
        $imgFile = $request->files->get("image");
        $userId = $viewer->getId();
        $path = $this->fileUploader->upload($imgFile, $this->getParameter('profile_pic_directory') . $userId . '/profile/', "profile_pic");
        $em = $this->getDoctrine()->getManager();
        $viewer->setProfilePicturePath($path);
        $em->flush();
        return $this->json(["message" => $path], 200);
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
        return $this->json($entities, 200, [], ['groups' => ['default', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'training_user']]);
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
        $res["lastTrainings"] = $serializer->normalize($trainings, null, ['groups' => ['default', 'summary']]);
        return $this->json($res);
    }

    #[Route('/{userId}/trainings', name: "user_training_list", methods: ["GET"])]
    public function getUserTrainingListAction(Request $request, int $userId): Response
    {
        $page = $request->get('page') ?? 0;
        $limit = $request->get('limit') ?? 5;
        $entities = $this->trainingRepository->findPaginatedByDate($userId, $page, $limit);
        return $this->json($entities, 200, [], ['groups' => ['default', 'training', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'training_user']]);
    }
}
