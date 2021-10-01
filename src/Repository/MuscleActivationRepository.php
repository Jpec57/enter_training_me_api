<?php

namespace App\Repository;

use App\Entity\MuscleActivation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MuscleActivation|null find($id, $lockMode = null, $lockVersion = null)
 * @method MuscleActivation|null findOneBy(array $criteria, array $orderBy = null)
 * @method MuscleActivation[]    findAll()
 * @method MuscleActivation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MuscleActivationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MuscleActivation::class);
    }

    // /**
    //  * @return MuscleActivation[] Returns an array of MuscleActivation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MuscleActivation
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
