<?php

namespace App\Controller;

use App\Entity\SavedTraining;
use App\Entity\User;
use App\Repository\SavedTrainingRepository;
use App\Repository\TrainingRepository;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/trainings')]
class TrainingController extends AbstractController
{
    private $trainingRepository;
    private $savedTrainingRepository;
    private $mailerService;
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        TrainingRepository $trainingRepository,
        SavedTrainingRepository $savedTrainingRepository,
        MailerService $mailerService
    ) {
        $this->savedTrainingRepository = $savedTrainingRepository;
        $this->trainingRepository = $trainingRepository;
        $this->mailerService = $mailerService;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: "training_list", methods: ["GET"])]
    public function list(): Response
    {
        $entities = $this->trainingRepository->findAll();
        return $this->json($entities, 200, [], ['groups' => ['default', 'training', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'exercise_cycle_exercise', 'training_exercise_cycle', 'training_user', 'exercise_cycle_exercise']]);
    }
    

    #[Route('/official', name: "official_training_list", methods: ["GET"])]
    public function officialList(): Response
    {
        $entities = $this->trainingRepository->findBy(['isOfficial' => true]);
        return $this->json($entities, 200, [], ['groups' => ['default', 'training', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'exercise_cycle_exercise', 'training_exercise_cycle', 'training_user', 'exercise_cycle_exercise']]);
    }

    #[Route('/reference/{id}', name: "training_list_by_reference", methods: ["GET"])]
    public function listByReference(int $id): Response
    {
        $entities = $this->trainingRepository->findBy(['reference' => $id]);
        return $this->json($entities, 200, [], ['groups' => ['default', 'training',  'realised_exercise_set', 'realised_exercise_exercise_reference', 'exercise_cycle_exercise', 'training_exercise_cycle', 'training_user', 'exercise_cycle_exercise']]);
    }

    #[Route('/summary', name: "training_summary", methods: ["GET"])]
    public function summary(): Response
    {
        $entities = $this->trainingRepository->findAll();
        return $this->json($entities, 200, [], ['groups' => ['summary']]);
    }

    #[Route('/{id}/share', name: "training_share_by_email", methods: ["GET"])]
    public function shareTrainingByEmail(int $id): Response
    {
        $entity = $this->trainingRepository->findOneBy(['id' => $id]);
        $trainingJson = $this->json($entity, 200, [], ['groups' => ['summary']]);
        $this->mailerService->sendTrainingEmail(json_encode($trainingJson->getContent()));
        return $this->json(['res' => "ok"], 200);
    }

    #[Route('/{id}/save', name: "training_save", methods: ["GET"])]
    public function saveTrainingAction(int $id): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        if (is_null($viewer)) {
            return $this->json(["error" => "You must be connected to save training"], 403);
        }
        if (count($this->savedTrainingRepository->findBy(['trainingReference' => $id, 'user' => $viewer])) > 0) {
            return $this->json(['res' => "already saved"], 200);
        }
        $entity = $this->trainingRepository->findOneBy(['id' => $id]);
        $savedTraining = new SavedTraining();
        $savedTraining->setTrainingReference($entity);
        $savedTraining->setUser($viewer);
        $viewer->addSavedTraining($savedTraining);
        $this->entityManager->flush();
        return $this->json(['res' => "ok"], 200);
    }

    #[Route('/{id}/unsave', name: "training_unsave", methods: ["GET"])]
    public function unsaveTrainingAction(int $id): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        if (is_null($viewer)) {
            return $this->json(["error" => "You must be connected to unsave training"], 403);
        }
        $entity = $this->savedTrainingRepository->findOneBy(['trainingReference' => $id]);
        if (is_null($entity)) {
            return $this->json(['error' => "not found"], 400);
        }
        $viewer->removeSavedTraining($entity);
        $this->entityManager->flush();
        return $this->json(['res' => "ok"], 200);
    }


    #[Route('/saved', name: "training_saved_list", methods: ["GET"])]
    public function savedTrainingListAction(): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        $trainings = [];

        if (is_null($viewer)) {
            return $this->json([], 403);
        }
        $entities = $this->savedTrainingRepository->findBy(['user' => $viewer], ['createdDate' => 'DESC']);
        foreach ($entities as $entity) {
            $trainings[] = $entity->getTrainingReference();
        }
        return $this->json($trainings, 200, [], ['groups' => ['default', 'training', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'exercise_cycle_exercise', 'training_exercise_cycle', 'training_user', 'exercise_cycle_exercise']]);
    }
}
