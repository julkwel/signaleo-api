<?php

namespace App\Repository;

use App\Entity\Actualite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Actualite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Actualite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Actualite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActualiteRepository extends ServiceEntityRepository
{
    /**
     * ActualiteRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Actualite::class);
    }

    /**
     * @param int|null $limit
     *
     * @return Actualite[]|array
     */
    public function findAll(?int $limit = 10)
    {
        return $this->findBy([], ['id' => 'DESC'], $limit);
    }

    /**
     * @param int|null $limit
     * @param int      $page
     *
     * @return Actualite[]|array
     */
    public function findPaginated(?int $limit = 10, $page = 0)
    {
        $query = $this->createQueryBuilder('a')->orderBy('a.id', 'DESC')->getQuery();
        $paginator = new Paginator($query);
        $paginator->getQuery()->setFirstResult($page)->setMaxResults($limit);

        $list = [];
        foreach ($paginator as $value) {
            $list[] = $value;
        }

        return $list;
    }

    /**
     * @param string|null $needle
     * @param int|null    $limit
     *
     * @return mixed
     */
    public function search(?string $needle, ?int $limit = 10)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->andWhere('a.lieu LIKE :param OR a.message LIKE :param OR a.type LIKE :param')
            ->setParameter('param', '%'.$needle.'%')
            ->setMaxResults($limit)
            ->orderBy('a.id', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
