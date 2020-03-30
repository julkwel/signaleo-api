<?php
/**
 * @author <Bocasay>.
 */

namespace App\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserManager.
 */
class UserManager extends AbstractManager
{
    /**
     * UserManager constructor.
     *
     * @param UserRepository               $userRepository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        parent::__construct($userRepository, $userPasswordEncoder);
    }

    /**
     * @param array $data
     *
     * @return User
     *
     * @throws \Exception
     */
    public function handleUser(array $data)
    {
        $user = new User();
        if (isset($data['id']) && '' !== $data['id'] && $data['id']) {
            $user = $this->userRepository->find($data['id']);
        }

        $user->setEmail($data['email'] ?? 'notfound@gmail.com')
            ->setPassword($this->encoder->encodePassword($user, $data['password'] ?? '123456'))
            ->setRoles(['ROLE_USER'])
            ->setName($data['name'] ?? 'Signaleo user')
            ->setGender($data['gender'] ?? 'Lahy');

        return $user;
    }
}
