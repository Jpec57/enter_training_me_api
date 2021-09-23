<?php

namespace App\Repository;

use App\Entity\ExecutionStyle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExecutionStyle|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExecutionStyle|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExecutionStyle[]    findAll()
 * @method ExecutionStyle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExecutionStyleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExecutionStyle::class);
    }

    // /**
    //  * @return ExecutionStyle[] Returns an array of ExecutionStyle objects
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
    public function findOneBySomeField($value): ?ExecutionStyle
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
