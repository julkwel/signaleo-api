<?php

namespace App\Repository;

use App\Entity\Societe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Societe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Societe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Societe[]    findAll()
 * @method Societe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocieteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Societe::class);
    }

    /**
     * @param string          $value
     * @param int|string|null $limit
     *
     * @return Societe[] Returns an array of Societe objects
     */
    public function findByName(string $value, $limit = 10)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.nom = :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('s.id', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string|null     $etoile
     * @param int|string|null $limit
     *
     * @return mixed
     */
    public function findByEtoils(?string $etoile, $limit = 10)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.stars = :val')
            ->setParameter('val', '%'.$etoile.'%')
            ->orderBy('s.id', 'ASC')
            ->setMaxResults($etoile)
            ->getQuery()
            ->getResult();
    }
}
