<?php

namespace App\Repository;

use App\Entity\Station;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Station|null find($id, $lockMode = null, $lockVersion = null)
 * @method Station|null findOneBy(array $criteria, array $orderBy = null)
 * @method Station[]    findAll()
 * @method Station[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Station::class);
    }

    /**
     * @param string $region
     *
     * @return Station[] Returns an array of Station objects
     */
    public function findByRegion(string $region)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.region = :val')
            ->setParameter('val', $region)
            ->orderBy('s.distributeur', 'ASC')
            ->getQuery()->getResult();
    }

    /**
     * @return mixed
     */
    public function findAllRegion()
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('s.region');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string|null $needle
     * @param string      $region
     * @param int         $limit
     *
     * @return mixed
     */
    public function search(?string $needle, $region = 'Analamanga', $limit = 10)
    {
        $qb = $this->createQueryBuilder('s');
        $qb->andWhere('s.region LIKE :param OR s.commune LIKE :param OR s.district LIKE :param OR s.localites LIKE :param OR s.nomStation LIKE :param OR s.distributeur LIKE :param')
            ->andWhere('s.region = :region')
            ->setParameter('region', $region)
            ->setParameter('param', '%'.$needle.'%')
            ->orderBy('s.distributeur', 'ASC');

        return $qb->getQuery()->setMaxResults($limit)->getResult();
    }
}
