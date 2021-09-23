<?php

namespace App\Repository;

use App\Entity\ExerciceCycle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExerciceCycle|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExerciceCycle|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExerciceCycle[]    findAll()
 * @method ExerciceCycle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExerciceCycleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExerciceCycle::class);
    }

    // /**
    //  * @return ExerciceCycle[] Returns an array of ExerciceCycle objects
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
    public function findOneBySomeField($value): ?ExerciceCycle
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
