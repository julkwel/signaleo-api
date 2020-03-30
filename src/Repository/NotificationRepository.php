<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    /**
     * NotificationRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    /**
     * @param $user
     *
     * @return Notification[] Returns an array of Notification objects
     */
    public function findByUser($user)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.user = :val')
            ->andWhere('n.isView = :isView')
            ->setParameter('isView', false)
            ->setParameter('val', $user)
            ->orderBy('n.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param     $user
     *
     * @param int $page
     * @param int $limit
     *
     * @return array Returns an array of Notification objects
     */
    public function findViewedNotif($user, $page = 0, $limit = 10)
    {
        $query = $this->createQueryBuilder('n')
            ->andWhere('n.user = :val')
            ->setParameter('val', $user)
            ->orderBy('n.id', 'ASC')
            ->getQuery();

        $paginator = new Paginator($query);
        $paginator->getQuery()->setFirstResult($page)->setMaxResults($limit);

        $list = [];
        foreach ($paginator as $value) {
            $list[] = $value;
        }

        return $list;
    }
}
