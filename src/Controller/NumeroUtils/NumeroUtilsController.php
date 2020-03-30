<?php
/**
 * Julien Rajerison <julienrajerison5@gmail.com>
 **/

namespace App\Controller\NumeroUtils;

use App\Controller\AbstractBaseController;
use App\Entity\NumeroUtils;
use App\Repository\NumeroUtilsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/numero")
 *
 * Class NumeroUtilsController.
 */
class NumeroUtilsController extends AbstractBaseController
{
    /**
     * @Route("/list",name="list_numeros")
     *
     * @param NumeroUtilsRepository $numeroUtilsRepository
     *
     * @return JsonResponse
     */
    public function findListNumero(NumeroUtilsRepository $numeroUtilsRepository)
    {
        $data = $numeroUtilsRepository->findBy([], ['type' => 'DESC']);

        return new JsonResponse(['data' => $this->handleData($data)]);
    }

    /**
     * @Route("/search",name="search_numero")
     *
     * @param NumeroUtilsRepository $numeroUtilsRepository
     * @param Request               $request
     *
     * @return JsonResponse
     */
    public function search(NumeroUtilsRepository $numeroUtilsRepository, Request $request)
    {
        $search = json_decode($request->getContent(), true);
        $data = $numeroUtilsRepository->search($search['search'] ?? '');

        return new JsonResponse(['data' => $this->handleData($data)]);
    }

    /**
     * @param mixed $data
     *
     * @return array
     */
    public function handleData($data)
    {
        $list = [];
        foreach ($data as $key => $item) {
            $list[$key]['name'] = $item->getNom();
            $list[$key]['type'] = $item->getType();
            $list[$key]['numero'] = $item->getNumero();
        }

        return $list;
    }
}