<?php

namespace App\Controller;

use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TestController extends AbstractController
{

    private $env;
    private $trainingRepository;

    public function __construct(TrainingRepository $trainingRepository, string $env)
    {
        $this->trainingRepository = $trainingRepository;
        $this->env = $env;
    }

    #[Route('/test', name: 'test')]
    public function index(): Response
    {
        $env = $this->env;
        return $this->json([
            'controller_name' => "TestController $env",
        ]);
    }
}
