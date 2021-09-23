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

    // /**
    //  * @return ExerciseReference[] Returns an array of ExerciseReference objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExerciseReference
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
