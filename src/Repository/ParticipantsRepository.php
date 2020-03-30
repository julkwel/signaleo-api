<?php
/**
 * @author RAJERISON Julien <julienrajerison@gmail.com>.
 */

namespace App\Repository;

use App\Entity\Participants;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Participants|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participants|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participants[]    findAll()
 * @method Participants[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participants::class);
    }
}
