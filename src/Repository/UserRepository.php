<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string $needle
     *
     * @return array Returns an array of User data
     */
    public function searchUser(string $needle)
    {
        $data = $this->createQueryBuilder('u')
            ->andWhere('u.name = :name')
            ->andWhere('u.email = :email')
            ->setParameter('name', '%'.$needle.'%')
            ->setParameter('email', '%'.$needle.'%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;

        $lists = [];

        /**
         * @var int $key
         * @var User $item
         */
        foreach ($data as $key=>$item){
            $lists[$key]['name'] = $item->getName();
            $lists[$key]['email'] = $item->getEmail();
            $lists[$key]['id'] = $item->getId();
        }

        return $lists;
    }
}
