<?php

namespace App\Repository;

use App\Entity\NumeroUtils;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method NumeroUtils|null find($id, $lockMode = null, $lockVersion = null)
 * @method NumeroUtils|null findOneBy(array $criteria, array $orderBy = null)
 * @method NumeroUtils[]    findAll()
 * @method NumeroUtils[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NumeroUtilsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NumeroUtils::class);
    }

    /**
     * @param string|null $needle
     *
     * @return mixed
     */
    public function search(?string $needle)
    {
        $qb = $this->createQueryBuilder('n');
        $qb->andWhere('n.nom LIKE :param OR n.type LIKE :param OR n.numero LIKE :param')
            ->setParameter('param', '%'.$needle.'%')
            ->orderBy('n.type', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
