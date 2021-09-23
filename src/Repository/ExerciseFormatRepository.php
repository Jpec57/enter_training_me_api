<?php

namespace App\Repository;

use App\Entity\ExerciseFormat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExerciseFormat|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExerciseFormat|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExerciseFormat[]    findAll()
 * @method ExerciseFormat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExerciseFormatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExerciseFormat::class);
    }

    // /**
    //  * @return ExerciseFormat[] Returns an array of ExerciseFormat objects
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
    public function findOneBySomeField($value): ?ExerciseFormat
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
