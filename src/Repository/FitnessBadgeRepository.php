<?php

namespace App\Repository;

use App\Entity\FitnessBadge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FitnessBadge|null find($id, $lockMode = null, $lockVersion = null)
 * @method FitnessBadge|null findOneBy(array $criteria, array $orderBy = null)
 * @method FitnessBadge[]    findAll()
 * @method FitnessBadge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FitnessBadgeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FitnessBadge::class);
    }

    // /**
    //  * @return FitnessBadge[] Returns an array of FitnessBadge objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FitnessBadge
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
