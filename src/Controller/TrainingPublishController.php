<?php

namespace App\Controller;

use App\Entity\Training;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;


#[AsController]
class TrainingPublishController extends AbstractController
{
    public function __invoke(Training $training): Training
    {
        $training->setAuthor(null);
        return $training;
    }
}
