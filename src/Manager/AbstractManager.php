<?php
/**
 * Julien Rajerison <julienrajerison5@gmail.com>
 **/

namespace App\Manager;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AbstractManager.
 */
abstract class AbstractManager
{
    /** @var UserRepository */
    protected $userRepository;

    /** @var UserPasswordEncoderInterface */
    protected $encoder;

    /**
     * AbstractManager constructor.
     *
     * @param UserRepository               $userRepository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->encoder = $userPasswordEncoder;
    }
}