<?php
/**
 * @author <Bocasay>.
 */

namespace App\Manager;

use App\Entity\Voting;
use App\Repository\UserRepository;
use App\Repository\VotingRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class VoteManager.
 */
class VoteManager extends AbstractManager
{
    private $voteRepository ;

    /**
     * VoteManager constructor.
     *
     * @param UserRepository               $userRepository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param VotingRepository             $votingRepository
     */
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $userPasswordEncoder,VotingRepository $votingRepository)
    {
        parent::__construct($userRepository, $userPasswordEncoder);
        $this->voteRepository = $votingRepository;
    }

    /**
     * @param array $data
     *
     * @return Voting
     *
     * @throws \Exception
     */
    public function addVote(array $data)
    {
        $vote = new Voting();
        if($data['vote'] === 'marina' || $data['vote'] === 'diso' || $data['vote'] === 'haha'){
            $vote
                ->setType($data['vote'])
                ->setUser($this->userRepository->find($data['user']));
        }

        return $vote;
    }
}
