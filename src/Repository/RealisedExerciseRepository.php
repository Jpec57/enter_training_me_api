<?php

namespace App\Repository;

use App\Entity\RealisedExercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RealisedExercise|null find($id, $lockMode = null, $lockVersion = null)
 * @method RealisedExercise|null findOneBy(array $criteria, array $orderBy = null)
 * @method RealisedExercise[]    findAll()
 * @method RealisedExercise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RealisedExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RealisedExercise::class);
    }


    public function findReferenceExerciseIdsByUser(int $userId)
    {
        $params = [
            'userId' => $userId
        ];

        $qb = $this->createQueryBuilder('e')
            ->select('ref.id as referenceId')
            ->leftJoin('e.exerciseReference', 'ref')
            ->leftJoin('e.sets', 's')
            ->andWhere('s.user = :userId')
            ->groupBy('ref.id');

        $res = $qb
            ->setParameters($params)
            ->getQuery()
            ->getArrayResult();

        return array_map(function ($item) {
            return $item['referenceId'];
        }, $res);
    }
}
