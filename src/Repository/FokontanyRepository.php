<?php

namespace App\Repository;

use App\Entity\Fokontany;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Fokontany|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fokontany|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fokontany[]    findAll()
 * @method Fokontany[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FokontanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fokontany::class);
    }

    /**
     * @param string $value
     *
     * @return Fokontany[] Returns an array of Fokontany objects
     */
    public function findFokontany(string $value)
    {
        $qb = $this->createQueryBuilder('f');

        return $qb->andWhere($qb->expr()->like('f.name', ':val'))
            ->setParameter('val', $value.'%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
