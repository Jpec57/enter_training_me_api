<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class TestController extends AbstractController
{
    #[Route('/test', name: 'test')]
    public function index(): Response
    {
        return $this->json([
            'controller_name' => 'TestController',
        ]);
    }


    public function publish(HubInterface $hub): Response
    {
        $update = new Update(
            '/books/1',
            json_encode(['status' => 'OutOfStock'])
        );
        $hub->publish($update);

        return $this->json(["message" => "published"]);
    }
}
