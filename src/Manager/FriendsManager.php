<?php
/**
 * @author RAJERISON Julien <julienrajerison@gmail.com>.
 */

namespace App\Manager;

use App\Entity\Friends;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class FriendsManager.
 */
class FriendsManager extends AbstractManager
{
    /** @var EntityManagerInterface */
    private $manager;

    /**
     * FriendsManager constructor.
     *
     * @param UserRepository               $userRepository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param EntityManagerInterface       $entityManager
     */
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager)
    {
        parent::__construct($userRepository, $userPasswordEncoder);
        $this->manager = $entityManager;
    }

    /**
     * @param Request $request
     *
     * @return User|null
     */
    public function handleFriendsRequest(Request $request)
    {
        $user = $this->userRepository->find($request->get('user'));
        $userSent = $this->userRepository->find($request->get('userSent'));
        $friend = new Friends();
        $friend->setUser($user);
        $friend->setIsAccepted(false);

        $this->manager->persist($friend);
        $userSent->addFriend($friend);

        return $userSent;
    }
}