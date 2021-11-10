<?php

namespace App\Repository;

use App\Entity\UserReaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserReaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserReaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserReaction[]    findAll()
 * @method UserReaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserReactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserReaction::class);
    }

    // /**
    //  * @return UserReaction[] Returns an array of UserReaction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserReaction
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
