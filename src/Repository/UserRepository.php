<?php

namespace App\Repository;

use App\Entity\User;
use App\Enum\MuscleEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findOrderedByRanking(string $rankingType = "global")
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u as user')
            ->leftJoin('u.fitnessProfile', 'p');
        switch ($rankingType) {
            case MuscleEnum::CHEST:
                $qb = $qb
                    ->addSelect('p.chestExperience AS experience')
                    ->orderBy('p.chestExperience', 'DESC');
                break;
            case MuscleEnum::BACK:
                $qb = $qb
                    ->addSelect('p.backExperience AS experience')

                    ->orderBy('p.backExperience', 'DESC');
                break;
            case MuscleEnum::TRICEPS:
                $qb = $qb
                    ->addSelect('p.tricepsExperience AS experience')

                    ->orderBy('p.tricepsExperience', 'DESC');
                break;
            case MuscleEnum::FOREARMS:
            case MuscleEnum::BICEPS:
                $qb = $qb
                    ->addSelect('p.bicepsExperience AS experience')

                    ->orderBy('p.bicepsExperience', 'DESC');
                break;
            case MuscleEnum::SHOULDERS:
                $qb = $qb
                    ->addSelect('p.shoulderExperience AS experience')

                    ->orderBy('p.shoulderExperience', 'DESC');
                break;
            case MuscleEnum::QUADRICEPS:
                $qb = $qb
                    ->addSelect('p.quadricepsExperience AS experience')

                    ->orderBy('p.quadricepsExperience', 'DESC');
                break;
            case MuscleEnum::HAMSTRING:
                $qb = $qb
                    ->addSelect('p.hamstringExperience AS experience')
                    ->orderBy('p.hamstringExperience', 'DESC');
                break;
            case MuscleEnum::CALF:
                $qb = $qb
                    ->addSelect('p.calfExperience AS experience')

                    ->orderBy('p.calfExperience', 'DESC');
                break;
            case MuscleEnum::ABS:
                $qb = $qb
                    ->addSelect('p.absExperience AS experience')

                    ->orderBy('p.absExperience', 'DESC');
                break;
            default:
                $qb = $qb
                    ->addSelect('p.experience AS experience')
                    ->orderBy('p.experience', 'DESC');
                break;
        }

        return $qb->getQuery()
            ->getArrayResult();
    }
}
