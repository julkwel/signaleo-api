<?php
/**
 * @author <Bocasay>.
 */

namespace App\Controller\User;

use App\Controller\AbstractBaseController;
use App\Entity\Fokontany;
use App\Entity\User;
use App\Manager\UserManager;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController.
 */
class UserController extends AbstractBaseController
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /** @var UserManager */
    private $manager;

    /**
     * UserController constructor.
     *
     * @param EntityManagerInterface       $entityManager
     * @param SerializerUtils              $serializerUtils
     * @param UserPasswordEncoderInterface $encoder
     * @param UserManager                  $userManager
     */
    public function __construct(EntityManagerInterface $entityManager, SerializerUtils $serializerUtils, UserPasswordEncoderInterface $encoder, UserManager $userManager)
    {
        parent::__construct($entityManager, $serializerUtils);
        $this->encoder = $encoder;
        $this->manager = $userManager;
    }

    /**
     * @Route("/add/user/api", name="app_add_user")
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function inscription(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->manager->handleUser($data);

        if ($this->save($user)) {
            return new JsonResponse(['message' => 'success']);
        }

        return new JsonResponse(['message' => 'Une erreur c\'est produtie']);
    }

    /**
     * @Route("/api/user/list")
     *
     * @param UserRepository $userRepository
     *
     * @return JsonResponse
     */
    public function getListUser(UserRepository $userRepository)
    {
        $data = $userRepository->findAll();
        $lists = [];

        foreach ($data as $item) {
            $lists[] = json_decode($this->serializer->serialize($item));
        }

        return new JsonResponse(['list' => $lists]);
    }

    /**
     * @Route("/api/user/details/{id}",name="user_details_api")
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function getDetailsUser(User $user)
    {
        $thisUser['id'] = $user->getId();
        $thisUser['photo'] = $user->getAvatar();
        $thisUser['name'] = $user->getName();
        $thisUser['pseudo'] = $user->getPseudo();
        $thisUser['contact'] = $user->getEmail();
        $thisUser['gender'] = $user->getGender() ?? 'People';

        return new JsonResponse(['user' => $thisUser]);
    }

    /**
     * @Route("/api/user/notifications/{id}",name="user_notifications_mobile")
     *
     * @param User                   $user
     * @param NotificationRepository $notificationRepository
     *
     * @return JsonResponse
     */
    public function getNotifications(User $user, NotificationRepository $notificationRepository)
    {
        $notifs = $notificationRepository->findByUser($user);
        $datas = [];

        foreach ($notifs as $key => $notification) {
            $datas[$key]['title'] = $notification->getTitle();
            $datas[$key]['id'] = $notification->getId();
            $datas[$key]['dateAdd'] = $notification->getDateAdd()->format('d-m-Y H:i');
        }

        return new JsonResponse(['notifs' => $datas]);
    }

    /**
     * @Route("/api/user/notifications/count/{id}",name="count_new_notifs")
     *
     * @param User                   $user
     * @param NotificationRepository $notificationRepository
     *
     * @return JsonResponse
     */
    public function getCountNotifs(User $user, NotificationRepository $notificationRepository)
    {
        $notifs = count($notificationRepository->findByUser($user));

        return new JsonResponse(['notifs' => $notifs]);
    }

    /**
     * @Route("/api/user/notifications/all/{id}",name="user_notifications_list")
     *
     * @param Request                $request
     * @param User                   $user
     * @param NotificationRepository $notificationRepository
     *
     * @return JsonResponse
     */
    public function getAllNotifications(Request $request, User $user, NotificationRepository $notificationRepository)
    {
        $limit = $request->get('limit');
        $page = $request->get('page');

        $notifs = $notificationRepository->findViewedNotif($user, $page ? $page : 0, $limit + 10);
        $datas = [];

        foreach ($notifs as $key => $notification) {
            $datas[$key]['title'] = $notification->getTitle();
            $datas[$key]['id'] = $notification->getId();
            $datas[$key]['dateAdd'] = $notification->getDateAdd()->format('d-m-Y H:i');
        }

        return new JsonResponse(['notifs' => $datas]);
    }

    /**
     * @Route("/api/user/viewAll/notifications/{id}",name="user_view_notifications_api")
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function viewNotifications(User $user)
    {
        $notifs = $user->getNotifications();

        foreach ($notifs as $notification) {
            $notification->setIsView(true);
        }

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'success']);
    }

    /**
     * @Route("/api/user/delete/{id}",name="delete_user")
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function remove(User $user)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        if ($this->delete($user)) {
            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error']);
    }

    /**
     * @Route("/api/user/search",name="search_user")
     *
     * @param UserRepository $userRepository
     * @param Request        $request
     *
     * @return JsonResponse
     */
    public function findUser(UserRepository $userRepository, Request $request)
    {
        $term = $request->get('term');
        try {
            $data = $userRepository->searchUser($term);

            return new JsonResponse(['status' => 'success', 'data' => $data]);
        } catch (\Exception $exception) {
            return new JsonResponse(['status' => 'error']);
        }
    }

    /**
     * @Route("/api/user/friend/{id}",name="list_user_friends")
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function userFriends(User $user)
    {
        $friends = $user->getFriends();
        try {
            $list = [];
            foreach ($friends as $key => $friend) {
                $list[$key]['user'] = $friend->getUser()->getName();
                $list[$key]['dateFriend'] = $friend->getDateAccepted() ? $friend->getDateAccepted()->format('d-m-Y H:i') : 'Mbola miandry fankatoavana';
            }

            return new JsonResponse(['status' => 'success', 'data' => $list]);
        } catch (\Exception $exception) {
            return new JsonResponse(['status' => 'error']);
        }
    }


//    /**
//     * Dump fokontany csv to sql
//     *
//     * @Route("/parse/pdf",name="parse_pdf")
//     *
//     * @param ParameterBagInterface $parameterBag
//     */
//    public function parsePdf(ParameterBagInterface $parameterBag)
//    {
//        $filePath = $parameterBag->get('file_upload').'../dump/fkt.csv';
//        $handle = fopen($filePath, "r");
//
//        for ($i = 0; $row = fgetcsv($handle, 0, ';'); ++$i) {
//            $fokontany = new Fokontany();
//            if ($i >= 1) {
//                $fokontany->setName($row[0]);
//                $fokontany->setCommune($row[1]);
//                $this->entityManager->persist($fokontany);
//            }
//        }
//
//        $this->entityManager->flush();
//
//        fclose($handle);
//        dd('success');
//    }
}
