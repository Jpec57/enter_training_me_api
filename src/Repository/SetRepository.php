<?php

namespace App\Repository;

use App\Entity\Set;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Set|null find($id, $lockMode = null, $lockVersion = null)
 * @method Set|null findOneBy(array $criteria, array $orderBy = null)
 * @method Set[]    findAll()
 * @method Set[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Set::class);
    }


    public function findByReferenceExo(int $refExoId, ?int $userId = null)
    {
        $params = [
            'refExoId' => $refExoId
        ];

        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.realisedExercise', 'doneExo')
            ->leftJoin('doneExo.exerciseReference', 'refExo')
            ->andWhere('refExo = :refExoId');

        if ($userId) {
            $params['userId'] = $userId;
            $qb = $qb->andWhere("s.user = :userId");
        }

        return $qb
            ->setParameters($params)
            ->getQuery()
            ->getResult();
    }
}
