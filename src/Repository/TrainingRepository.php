<?php

namespace App\Repository;

use App\Entity\Training;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Training|null find($id, $lockMode = null, $lockVersion = null)
 * @method Training|null findOneBy(array $criteria, array $orderBy = null)
 * @method Training[]    findAll()
 * @method Training[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Training::class);
    }

    public function findForFeed(int $page = 0, int $limit = 10)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.isOfficial = 0')
            ->orderBy('t.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($page * $limit)
            ->getQuery()
            ->getResult();
    }


    public function countByUser(int $userId)
    {
        return $this->createQueryBuilder('t')
            ->setParameter("userId", $userId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
