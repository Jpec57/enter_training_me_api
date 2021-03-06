<?php

namespace App\Controller;

use App\Entity\ExerciseReference;
use App\Form\ExerciseReferenceType;
use App\Repository\ExerciseReferenceRepository;
use App\Traits\FormTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/exercises')]
class ExerciseReferenceController extends AbstractController
{
    use FormTrait;
    private ExerciseReferenceRepository $exerciseReferenceRepository;
    const MAXIMAL_CREATED_EXO_REF = 5;

    public function __construct(ExerciseReferenceRepository $exerciseReferenceRepository)
    {
        $this->exerciseReferenceRepository = $exerciseReferenceRepository;
    }

    #[Route('/', name: "exercise_list", methods: ["GET"])]
    public function list(): Response
    {
        $entities = $this->exerciseReferenceRepository->findAll();
        return $this->json($entities, 200, [], ['groups' => ['default']]);
    }

    #[Route('/', name: "exercise_create", methods: ["POST"])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        /** @var User $viewer */
        $viewer = $this->getUser();
        if (is_null($viewer)) {
            throw new UnauthorizedHttpException("You have to be logged in.");
        }

        $isAllowedToCreate = in_array("ROLE_ADMIN", $viewer->getRoles());
        if (!$isAllowedToCreate) {
            $alreadyCreatedExos = $this->exerciseReferenceRepository->findBy(['author' => $viewer, 'isValidated' => false]);
            if (count($alreadyCreatedExos) > self::MAXIMAL_CREATED_EXO_REF) {
                throw new UnauthorizedHttpException("Exceeding the number of authorized " . self::MAXIMAL_CREATED_EXO_REF);
            }
        }
        $entity = new ExerciseReference();
        $form = $this->createForm(ExerciseReferenceType::class, $entity);
        $form->submit($request->request->all(), false);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entity);
            $em->flush();
            return $this->json($entity, 201, [], ['groups' => ['default']]);
        }
        $errors = $this->getErrorsFromForm($form);
        return $this->json(["errors" => $errors], 400);
    }

    #[Route('/{id}', name: "exercise_show", methods: ["GET"])]
    public function show(ExerciseReference $entity): Response
    {
        return $this->json($entity, 200, [], ['groups' => ['default']]);
    }

    #[Route('/{id}', name: "exercise_update", methods: ["PUT", "PATCH"])]
    public function update(ExerciseReference $entity, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ExerciseReferenceType::class, $entity);
        $form->handleRequest();
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->json($entity, 201, [], ['groups' => ['default']]);
        }
        $errors = $this->getErrorsFromForm($form);
        return $this->json(["errors" => $errors], 400);
    }

    #[Route('/{id}', name: "exercise_remove", methods: ["DELETE"])]
    public function remove(ExerciseReference $exercise, EntityManagerInterface $em): Response
    {
        if ($exercise) {
            $em->remove($exercise);
            $em->flush();
            return $this->json(['message' => "remove"], 204);
        }
        $errors = ["No exercise found"];
        return $this->json(['errors' => $errors], 400);
    }
}
