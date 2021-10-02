<?php

namespace App\Repository;

use App\Entity\ExerciseReference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExerciseReference|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExerciseReference|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExerciseReference[]    findAll()
 * @method ExerciseReference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExerciseReferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExerciseReference::class);
    }
}
