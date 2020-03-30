<?php
/**
 * @author RAJERISON Julien <julienrajerison@gmail.com>.
 */

namespace App\Repository;

use App\Entity\Todo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class TodoRepository.
 *
 * @method findAll()
 * @method find($id, $lockMode = null, $lockVersion = null)
 * @method findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method findOneBy(array $criteria, array $orderBy = null)
 */
class TodoRepository extends ServiceEntityRepository
{
    /**
     * TodoRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Todo::class);
    }
}