<?php
/**
 * Julien Rajerison <julienrajerison5@gmail.com>
 **/

namespace App\Controller\Actualite;

use App\Controller\AbstractBaseController;
use App\Entity\Actualite;
use App\Entity\Comment;
use App\Entity\Notification;
use App\Entity\User;
use App\Entity\Voting;
use App\Manager\ActualiteManager;
use App\Manager\VoteManager;
use App\Repository\ActualiteRepository;
use App\Repository\FokontanyRepository;
use App\Repository\UserRepository;
use App\Repository\VotingRepository;
use App\Utils\HtmlToEmoji;
use App\Utils\SerializerUtils;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use OneSignal\Config;
use OneSignal\OneSignal;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Client\Common\HttpMethodsClient as HttpClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;

/**
 * Class ActualiteController.
 *
 * @Route("/api/actualite")
 */
class ActualiteController extends AbstractBaseController
{
    public const MARINA = 'marina';
    public const DISO = 'diso';
    public const POINT = 1;
    public const HAHA = 'haha';

    /** @var mixed */
    private $filePath;

    /** @var UserRepository */
    private $userRepos;

    /** @var ActualiteManager */
    private $manager;

    /** @var VoteManager */
    private $voteManager;

    /** @var VotingRepository */
    private $voteRepository;

    /** @var ActualiteRepository */
    private $actualiteRepos;

    /**
     * ActualiteController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param SerializerUtils        $serializerUtils
     * @param ParameterBagInterface  $filePath
     * @param UserRepository         $repository
     * @param ActualiteManager       $actualiteManager
     * @param VoteManager            $voteManager
     * @param VotingRepository       $votingRepository
     * @param ActualiteRepository    $actualiteRepository
     */
    public function __construct(EntityManagerInterface $entityManager, SerializerUtils $serializerUtils, ParameterBagInterface $filePath, UserRepository $repository, ActualiteManager $actualiteManager, VoteManager $voteManager, VotingRepository $votingRepository, ActualiteRepository $actualiteRepository)
    {
        parent::__construct($entityManager, $serializerUtils);
        $this->filePath = $filePath->get('file_upload');
        $this->userRepos = $repository;
        $this->manager = $actualiteManager;
        $this->voteManager = $voteManager;
        $this->voteRepository = $votingRepository;
        $this->actualiteRepos = $actualiteRepository;
    }

    /**
     * @Route("/manage",name="add_actualite")
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function addActualite(Request $request)
    {
        $files = $request->files->get('image');
        $actualite = $this->manager->addActualite($request);

        $routeName = $request->getSchemeAndHttpHost();
        $newFilename = null;

        if ($files) {
            $originalFilename = pathinfo($files->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$files->guessExtension();

            try {
                $files->move($this->filePath, $newFilename);
                $actualite->setPhoto($routeName.'/image/'.$newFilename);

                $optimizerChain = OptimizerChainFactory::create();
                $optimizerChain->optimize($this->filePath.$newFilename);
            } catch (FileException $e) {
                $actualite->setPhoto($routeName.'/image/embouteillage.jpg');

                return new JsonResponse(['status' => 'Unable to upload image']);
            }
        }

        if ($this->save($actualite)) {
            $this->getOneSignal()->notifications->add([
                'contents' => [
                    'en' => $actualite->getLieu().' - '.$actualite->getType(),
                ],
                'included_segments' => ['All'],
                'send_after' => new DateTime('now'),
                'data' => ['signaleo' => 'Tantarao ny zava-misy'],
                "url" => $request->getSchemeAndHttpHost(),
            ]);

            return new JsonResponse(['status' => 200]);
        }

        return new JsonResponse(['status' => 'error']);
    }

    /**
     * @Route("/list",name="list_actualite")
     *
     * @param ActualiteRepository $repository
     * @param Request             $request
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function getActualite(ActualiteRepository $repository, Request $request)
    {
        $limit = $request->get('limit');
        $user = $request->get('user');
        $search = $request->get('search');
        $web = $request->get('web');
        $page = $request->get('page');

        if ($user && '' !== $user)
            $user = $this->userRepos->find($user);

        $data = $web ? $repository->findPaginated($limit + 10, $page ? $page : 0) : $repository->findAll($limit + 10);

        if ($search && '' !== $search) {
            $data = $repository->search($search, $limit + 10);
        }

        if ($user instanceof User) {
            try {
                $lists = $this->manager->handleActualiteData($data, $user);

                return new JsonResponse(['data' => $lists]);
            } catch (Exception $exception) {
                return new JsonResponse(['data' => 'error '.$exception->getMessage()]);
            }
        }

        return new JsonResponse(['data' => 'nouser']);
    }

    /**
     * @return OneSignal
     */
    public function getOneSignal()
    {
        $config = new Config();
        $config->setApplicationId('f72b9ebd-4e68-4c99-8187-b6f521fdaf3d');
        $config->setApplicationAuthKey('NTZiOWUzOTctYTc1YS00NmUyLTlkY2YtMDkxMTEwNzIxM2Y4');
        $config->setUserAuthKey('ZmE1ZjM5OTEtM2ZmMC00YzMyLTgxNzctM2YyNjY2Y2RiNjJh');

        $guzzle = new GuzzleClient([
            'base_uri' => 'https://signaleo.techzara.org',
            'timeout' => 2.0,
        ]);
        $client = new HttpClient(new GuzzleAdapter($guzzle), new GuzzleMessageFactory());

        return new OneSignal($config, $client);
    }

    /**
     * @Route("/delete/{id}/{user}",name="remove_actualite")
     *
     * @param Actualite $actualite
     * @param User      $user
     *
     * @return JsonResponse
     */
    public function removeActualite(Actualite $actualite, User $user = null)
    {
        if ($user && ($actualite->getUser() === $user)) {
            if ($this->delete($actualite)) {
                return new JsonResponse(['status' => 'success']);
            }
        }

        return new JsonResponse(['status' => 'error']);
    }

    /**
     * @Route("/vote/{id}",name="vote_actualite")
     *
     * @param Request   $request
     * @param Actualite $actualite
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function addVoting(Request $request, Actualite $actualite)
    {
        $data = json_decode($request->getContent(), true);
        $vote = $this->voteManager->addVote($data);

        $user = $this->userRepos->find($data['user']);
        $currentVote = $actualite->getVote()->getValues();

        $userPost = $actualite->getUser();

        /** @var Voting $item */
        foreach ($currentVote as $item) {
            if ($item->getUser() === $user) {
                $actualite->removeVote($item);

                if ($actualite->getUser() !== $user) {
                    if (self::MARINA === $item->getType()) {
                        $userPost->setPoint($userPost->getPoint() - self::POINT);
                    } elseif (self::DISO === $item->getType()) {
                        $userPost->setPoint($userPost->getPoint() + self::POINT);
                    }
                }

                $this->entityManager->remove($item);
            }
        }

        $actualite->addVote($vote);

        if ($actualite->getUser() !== $user) {
            if (self::MARINA === $vote->getType()) {
                $userPost->setPoint($userPost->getPoint() + self::POINT);
            } elseif (self::DISO === $vote->getType()) {
                $userPost->setPoint($userPost->getPoint() - self::POINT);
            }
        }

        if ($this->save($actualite)) {
            if ($user !== $actualite->getUser()) {
                $notifications = new Notification();
                $notifications->setTitle('Nataon\'i '.$user->getName().' '.$vote->getType().' ny zavatra nozarainao !');
                $actualite->getUser()->addNotification($notifications);

                $this->entityManager->persist($notifications);
                $this->entityManager->flush();
            }

            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error']);
    }


    /**
     * @Route("/vote/remove/{id}",name="vote_remove")
     *
     * @param Request   $request
     * @param Actualite $actualite
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function removeVoting(Request $request, Actualite $actualite)
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->userRepos->find($data['user']);
        $currentVote = $actualite->getVote()->getValues();

        /** @var Voting $item */
        foreach ($currentVote as $item) {
            if ($item->getUser() === $user) {
                $actualite->removeVote($item);
                $this->entityManager->remove($item);
            }
        }
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'success']);
    }

    /**
     * @param Request             $request
     * @param FokontanyRepository $fokontanyRepository
     *
     * @Route("/fokontany/find",name="find_fokontany")
     *
     * @return JsonResponse
     */
    public function getFokontany(Request $request, FokontanyRepository $fokontanyRepository)
    {
        $data = json_decode($request->getContent(), true);
        $data = $fokontanyRepository->findFokontany($data['search'] ?? '');
        $lists = [];

        foreach ($data as $key => $actualite) {
            if (!in_array($actualite->getName(), $lists)) {
                $lists[$key]['label'] = $actualite->getName();
                $lists[$key]['value'] = $actualite->getName();
            }
        }

        return new JsonResponse(['data' => $lists]);
    }
}
