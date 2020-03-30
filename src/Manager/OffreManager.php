<?php
/**
 * Julien Rajerison <julienrajerison5@gmail.com>
 **/

namespace App\Manager;

use App\Entity\Offre;
use App\Repository\OffreRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class OffreManager.
 */
class OffreManager extends AbstractManager
{
    /** @var OffreRepository  */
    private $offreRepository;

    /**
     * OffreManager constructor.
     *
     * @param UserRepository               $userRepository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param OffreRepository              $offreRepository
     */
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $userPasswordEncoder, OffreRepository $offreRepository)
    {
        parent::__construct($userRepository, $userPasswordEncoder);
        $this->offreRepository = $offreRepository;
    }

    /**
     * @param array $data
     *
     * @return Offre
     *
     * @throws \Exception
     */
    public function handleOffre(array $data)
    {
        $offre = new Offre();
        $offre
            ->setUser($this->userRepository->find($data['userId'] ?? null))
            ->setDepart($data['depart'])
            ->setArrive($data['destination'])
            ->setNombreDePlace($data['nombreDePlace'] ?? 0)
            ->setContact($data['contact'] ?? '')
            ->setFrais($data['frais'] ?? null)
            ->setIsDispo(true)
            ->setDateDepart(new \DateTime($data['dateDepart'] ?? 'now'));

        return $offre;
    }
}