<?php

namespace App\Repository;

use App\Entity\SavedTraining;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SavedTraining|null find($id, $lockMode = null, $lockVersion = null)
 * @method SavedTraining|null findOneBy(array $criteria, array $orderBy = null)
 * @method SavedTraining[]    findAll()
 * @method SavedTraining[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SavedTrainingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SavedTraining::class);
    }

    // /**
    //  * @return SavedTraining[] Returns an array of SavedTraining objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SavedTraining
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
