<?php

namespace App\Repository;

use App\Entity\RealisedExercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RealisedExercise|null find($id, $lockMode = null, $lockVersion = null)
 * @method RealisedExercise|null findOneBy(array $criteria, array $orderBy = null)
 * @method RealisedExercise[]    findAll()
 * @method RealisedExercise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RealisedExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RealisedExercise::class);
    }

    // /**
    //  * @return RealisedExercise[] Returns an array of RealisedExercise objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RealisedExercise
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
