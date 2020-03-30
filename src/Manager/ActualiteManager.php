<?php
/**
 * Julien Rajerison <julienrajerison5@gmail.com>
 **/

namespace App\Manager;

use App\Entity\Actualite;
use App\Entity\User;
use App\Repository\ActualiteRepository;
use App\Repository\UserRepository;
use App\Repository\VotingRepository;
use App\Utils\HtmlToEmoji;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class EmboutekaManager.
 */
class ActualiteManager extends AbstractManager
{
    public const MARINA = 'marina';
    public const DISO = 'diso';
    public const POINT = 1;
    public const HAHA = 'haha';

    /** @var ActualiteRepository */
    private $emboutekaRepository;

    /** @var VotingRepository  */
    private $votingRepository;

    /** @var CommentManager $commentManager */
    private $commentManager;

    /**
     * EmboutekaManager constructor.
     *
     * @param UserRepository               $userRepository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param ActualiteRepository          $emboutekaRepository
     */
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $userPasswordEncoder, ActualiteRepository $emboutekaRepository,VotingRepository $votingRepository,CommentManager $commentManager)
    {
        parent::__construct($userRepository, $userPasswordEncoder);
        $this->emboutekaRepository = $emboutekaRepository;
        $this->votingRepository = $votingRepository;
        $this->commentManager = $commentManager;
    }

    /**
     * @param Request $request
     *
     * @return Actualite
     */
    public function addActualite(Request $request)
    {
        $data = $request->request->all();
        $embouteka = new Actualite();

        $embouteka
            ->setUser($this->userRepository->find($data['userId'] ?? ''))
            ->setLieu($data['lieu'] ?? '')
            ->setType($data['cause'])
            ->setMessage($data['message']);

        return $embouteka;
    }

    /**
     * @param array     $data
     * @param User|null $user
     *
     * @return array
     */
    public function handleActualiteData(array $data, User $user = null)
    {
        $lists = [];

        /**
         * @var int       $key
         * @var Actualite $actualite
         */
        foreach ($data as $key => $actualite) {
            $lists[$key]['id'] = $actualite->getId();
            $lists[$key]['photo'] = $actualite->getPhoto();
            $lists[$key]['comments'] = $this->commentManager->handleComments($actualite);
            $lists[$key]['user']['point'] = $actualite->getUser() ? ($actualite->getUser()->getPoint() ? $actualite->getUser()->getPoint() : 0) : 0;
            $lists[$key]['user']['name'] = $actualite->getUser() ? ($actualite->getUser()->getName() ?? 'Signaleo') : 'Signaleo';
            $lists[$key]['user']['gender'] = $actualite->getUser() ? ($actualite->getUser()->getGender() ?? 'People') : 'People';
            $lists[$key]['user']['id'] = $actualite->getUser() ? ($actualite->getUser()->getId() ?? null) : null;
            $lists[$key]['message'] = HtmlToEmoji::convertTextToEmoji($actualite->getMessage());
            $lists[$key]['type'] = $actualite->getType();
            $lists[$key]['lieu'] = $actualite->getLieu();
            $lists[$key]['dateAdd'] = $actualite->getDateAdd() ? $actualite->getDateAdd()->format("d-m-Y H:i") : 'Androany';
            $lists[$key]['vote']['marina'] = $this->getVoteNumber($actualite, self::MARINA);
            $lists[$key]['vote']['diso'] = $this->getVoteNumber($actualite, self::DISO);
            $lists[$key]['vote']['haha'] = $this->getVoteNumber($actualite, self::HAHA);
            $lists[$key]['vote']['user'] = $this->findUserVote($actualite, $user);
            $lists[$key]['actu']['isOk'] = $lists[$key]['vote']['marina'] > $lists[$key]['vote']['diso'];
        }

        return $lists;
    }

    /**
     * @param User      $user
     * @param Actualite $actualite
     *
     * @return mixed
     */
    public function findUserVote(Actualite $actualite, User $user = null)
    {
        return $this->votingRepository->findByUserVote($actualite, $user);
    }

    /**
     * @param Actualite $actualite
     * @param string    $typeVote
     *
     * @return int
     */
    public function getVoteNumber(Actualite $actualite, $typeVote)
    {
        return $this->votingRepository->findByActuVote($actualite, $typeVote);
    }
}
