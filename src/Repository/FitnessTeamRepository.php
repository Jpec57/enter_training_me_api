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

    public function findOrderedByRanking()
    {
        $qb = $this->createQueryBuilder('t')
            ->orderBy('t.experience', 'DESC');
        return $qb->getQuery()
            ->getResult();
    }
}
