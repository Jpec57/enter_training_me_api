<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Form\ExerciseType;
use App\Repository\ExerciseRepository;
use App\Traits\FormTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/exercises')]
class ExerciseController extends AbstractController
{
    use FormTrait;
    private ExerciseRepository $exerciseRepository;

    public function __construct(ExerciseRepository $exerciseRepository){
        $this->exerciseRepository = $exerciseRepository;
    }

    #[Route('/', name: "exercise_list", methods: ["GET"])]
    public function list(): Response
    {
        $entities = $this->exerciseRepository->findAll();
        return $this->json($entities, 200, [], ['groups' => ['default']]);
    }

    #[Route('/', name: "exercise_create", methods: ["POST"])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $entity = new Exercise();
        $form = $this->createForm(ExerciseType::class, $entity);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($entity);
            $em->flush();
            return $this->json($entity, 201, [], ['groups' => ['default']]);
        }
        $errors = $this->getErrorsFromForm($form);
        return $this->json(["errors" => $errors], 400);
    }

    #[Route('/{id}', name: "exercise_show", methods: ["GET"])]
    public function show(Exercise $entity): Response
    {
        return $this->json($entity, 200, [], ['groups' => ['default']]);
    }

    #[Route('/{id}', name: "exercise_update", methods: ["PUT", "PATCH"])]
    public function update(Exercise $entity, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ExerciseType::class, $entity);
        $form->handleRequest();
        if ($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->json($entity, 201, [], ['groups' => ['default']]);

        }
        $errors = $this->getErrorsFromForm($form);
        return $this->json(["errors" => $errors], 400);
    }

    #[Route('/{id}', name: "exercise_remove", methods: ["DELETE"])]
    public function remove(Exercise $exercise, EntityManagerInterface $em): Response
    {
        if ($exercise){
            $em->remove($exercise);
            $em->flush();
            return $this->json(['message' => "remove"], 204); 
        }
        $errors = ["No exercise found"];
        return $this->json(['errors' => $errors], 400); 
    } 

}
