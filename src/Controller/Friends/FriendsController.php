<?php
/**
 * @author RAJERISON Julien <julienrajerison@gmail.com>.
 */

namespace App\Controller\Friends;

use App\Controller\AbstractBaseController;
use App\Manager\FriendsManager;
use App\Repository\FriendsRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FriendsController.
 *
 * @Route("/api/friends")
 */
class FriendsController extends AbstractBaseController
{
    private $friendsManager;

    private $friendsRepository;

    /**
     * FriendsController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param SerializerUtils        $serializerUtils
     * @param FriendsManager         $friendsManager
     */
    public function __construct(EntityManagerInterface $entityManager, SerializerUtils $serializerUtils, FriendsManager $friendsManager, FriendsRepository $friendsRepository)
    {
        parent::__construct($entityManager, $serializerUtils);
        $this->friendsManager = $friendsManager;
        $this->friendsRepository = $friendsRepository;
    }

    /**
     * @Route("/request",name="friend_request")
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function friendsRequest(Request $request)
    {
        $userSent = $this->friendsManager->handleFriendsRequest($request);

        if ($this->save($userSent)) {
            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error']);
    }

    /**
     * @Route("/accepting",name="friend_accepting")
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function confirmFriends(Request $request)
    {
        $friend = $request->get('friendId');

        $friend = $this->friendsRepository->find($friend);
        $friend->setIsAccepted(true);
        $friend->setDateAccepted(new \DateTime('now'));

        if ($this->save($friend)) {
            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error']);
    }

    /**
     * @Route("/deleting",name="friend_deleting")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function rejectFriends(Request $request)
    {
        $friend = $request->get('friendId');
        $friend = $this->friendsRepository->find($friend);
        $this->entityManager->remove($friend);

        return new JsonResponse(['status' => 'success']);
    }
}