<?php

namespace App\Repository;

use App\Entity\FitnessTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FitnessTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method FitnessTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method FitnessTeam[]    findAll()
 * @method FitnessTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FitnessTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FitnessTeam::class);
    }

    // /**
    //  * @return FitnessTeam[] Returns an array of FitnessTeam objects
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
    public function findOneBySomeField($value): ?FitnessTeam
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
