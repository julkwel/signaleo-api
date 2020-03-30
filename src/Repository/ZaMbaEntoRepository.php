<?php

namespace App\Repository;

use App\Entity\ZaMbaEnto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method ZaMbaEnto|null find($id, $lockMode = null, $lockVersion = null)
 * @method ZaMbaEnto|null findOneBy(array $criteria, array $orderBy = null)
 * @method ZaMbaEnto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZaMbaEntoRepository extends ServiceEntityRepository
{
    /**
     * ZaMbaEntoRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ZaMbaEnto::class);
    }

    /**
     * @param int|null $limit
     *
     * @return ZaMbaEnto[]|array
     */
    public function findPaginated(?int $limit = 10)
    {
        return $this->findBy([], ['id' => 'DESC'], $limit);
    }

    /**
     * @param int $limit
     * @param int $page
     *
     * @return array
     */
    public function findPaginatedWeb($limit = 10, $page = 0)
    {
        $query = $this->createQueryBuilder('u')
            ->andWhere('u.depart != :depart')
            ->andWhere('u.arrive != :depart')
            ->setParameter('depart', '')
            ->orderBy('u.id', 'DESC')->getQuery();


        $paginator = new Paginator($query);
        $paginator->getQuery()->setFirstResult($page)->setMaxResults($limit);

        $list = [];
        foreach ($paginator as $value) {
            $list[] = $value;
        }

        return $list;
    }
}
