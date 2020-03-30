<?php
/**
 * Julien Rajerison <julienrajerison5@gmail.com>
 **/

namespace App\Manager;

use App\Entity\ZaMbaEnto;
use App\Repository\UserRepository;
use App\Repository\ZaMbaEntoRepository;
use DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ZaMbaEntoManager.
 */
class ZaMbaEntoManager extends AbstractManager
{
    /** @var ZaMbaEntoRepository */
    private $zaMbaEntoRepository;

    /**
     * ZaMbaEntoManager constructor.
     *
     * @param UserRepository               $userRepository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param ZaMbaEntoRepository          $mbaEntoRepository
     */
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $userPasswordEncoder, ZaMbaEntoRepository $mbaEntoRepository)
    {
        parent::__construct($userRepository, $userPasswordEncoder);
        $this->zaMbaEntoRepository = $mbaEntoRepository;
    }

    /**
     * @param array $data
     *
     * @return ZaMbaEnto
     *
     * @throws \Exception
     */
    public function handleData(array $data)
    {
        $zaMbaEnto = new ZaMbaEnto();
        $zaMbaEnto
            ->setUser($this->userRepository->find($data['userId'] ?? ''))
            ->setDepart($data['depart'] ?? 'Tanà')
            ->setArrive($data['arrive'] ?? 'Tanà')
            ->setContact($data['contact'] ?? '')
            ->setLieuExact($data['lieurecup'] ?? null)
            ->setNombre($data['nombre'] ?? 1)
            ->setPreference($data['transport'] ?? 'Fiara')
            ->setDateDepart(new DateTime($data['dateDepart'] ?? 'now'));

        return $zaMbaEnto;
    }
}
