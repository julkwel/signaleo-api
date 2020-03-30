<?php

namespace App\Repository;

use App\Entity\Actualite;
use App\Entity\User;
use App\Entity\Voting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Voting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voting[]    findAll()
 * @method Voting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VotingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voting::class);
    }

    /**
     * @param Actualite $actu
     * @param mixed     $value
     *
     * @return int Returns count an array of Voting objects
     */
    public function findByActuVote(Actualite $actu, $value)
    {
        return count($this->createQueryBuilder('v')
            ->andWhere('v.actualite = :actu')
            ->andWhere('v.type = :value')
            ->setParameter('actu', $actu)
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult());
    }

    /**
     * @param Actualite $actualite
     * @param User|null $user
     *
     * @return mixed
     */
    public function findByUserVote(Actualite $actualite, ?User $user = null)
    {
        $qb = $this->createQueryBuilder('v')->select('v.type');
        $qb->andWhere('v.user = :user')->andWhere('v.actualite = :actu')
            ->setParameter('user', $user)->setParameter('actu', $actualite);

        return $qb->getQuery()->getResult();
    }
}
