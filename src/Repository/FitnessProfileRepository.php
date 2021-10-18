<?php

namespace App\Repository;

use App\Entity\FitnessProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FitnessProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method FitnessProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method FitnessProfile[]    findAll()
 * @method FitnessProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FitnessProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FitnessProfile::class);
    }

    // /**
    //  * @return FitnessProfile[] Returns an array of FitnessProfile objects
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
    public function findOneBySomeField($value): ?FitnessProfile
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
