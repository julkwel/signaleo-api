<?php
/**
 * Julien Rajerison <julienrajerison5@gmail.com>
 **/

namespace App\Controller\ZaMbaEnto;

use App\Controller\AbstractBaseController;
use App\Entity\ZaMbaEnto;
use App\Manager\ZaMbaEntoManager;
use App\Repository\UserRepository;
use App\Repository\ZaMbaEntoRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ZaMbaEnto.
 *
 * @Route("/api/zambaento")
 */
class ZaMbaEntoController extends AbstractBaseController
{
    /** @var UserRepository */
    private $userRepos;

    /** @var ZaMbaEntoManager */
    private $zaMbaEnto;

    /**
     * ZaMbaEntoController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param SerializerUtils        $serializerUtils
     * @param UserRepository         $userRepos
     * @param ZaMbaEntoManager       $zaMbaEntoManager
     */
    public function __construct(EntityManagerInterface $entityManager, SerializerUtils $serializerUtils, UserRepository $userRepos, ZaMbaEntoManager $zaMbaEntoManager)
    {
        parent::__construct($entityManager, $serializerUtils);
        $this->userRepos = $userRepos;
        $this->zaMbaEnto = $zaMbaEntoManager;
    }

    /**
     * @Route("/manage",name="manage_zambaento")
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function add(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $zaMbaEnto = $this->zaMbaEnto->handleData($data);

        if ($this->save($zaMbaEnto)) {
            return new JsonResponse(['message' => 'success']);
        }

        return new JsonResponse(['message' => 'error']);
    }

    /**
     * @Route("/list",name="list_zambaento")
     *
     * @param ZaMbaEntoRepository $repository
     * @param Request             $request
     *
     * @return JsonResponse
     */
    public function listData(ZaMbaEntoRepository $repository, Request $request)
    {
        $limit = $request->get('limit');
        $page = $request->get('page');
        $web = $request->get('web');

        $data = $web ? $repository->findPaginatedWeb($limit + 10, $page ? $page : 0) : $repository->findPaginated($limit + 10);
        $lists = [];

        foreach ($data as $key => $zaMbaEnto) {
            $lists[$key]['id'] = $zaMbaEnto->getId();
            $lists[$key]['depart'] = $zaMbaEnto->getDepart();
            $lists[$key]['arrive'] = $zaMbaEnto->getArrive();
            $lists[$key]['user']['name'] = $zaMbaEnto->getUser() ? $zaMbaEnto->getUser()->getName() : 'Aho';
            $lists[$key]['dateDepart'] = $zaMbaEnto->getDateAdd() ? $zaMbaEnto->getDateAdd()->format('d-m-Y H:i') : 'Androany';
            $lists[$key]['contact'] = $zaMbaEnto->getContact();
            $lists[$key]['preference'] = $zaMbaEnto->getPreference();
            $lists[$key]['nombre'] = $zaMbaEnto->getNombre();
            $lists[$key]['lieurecup'] = $zaMbaEnto->getLieuExact();
        }

        return new JsonResponse(['data' => $lists]);
    }

    /**
     * @Route("/delete/{id}",name="delete_zambaento")
     *
     * @param ZaMbaEnto $zaMbaEnto
     *
     * @return JsonResponse
     */
    public function remove(ZaMbaEnto $zaMbaEnto)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        if ($this->delete($zaMbaEnto)) {
            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error']);
    }
}
