<?php
/**
 * Julien Rajerison <julienrajerison5@gmail.com>
 **/

namespace App\Controller\Stations;

use App\Controller\AbstractBaseController;
use App\Repository\StationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/station")
 *
 * Class StationController.
 */
class StationController extends AbstractBaseController
{
    /**
     * @Route("/list/region",name="region_list")
     *
     * @param StationRepository $stationRepository
     *
     * @return JsonResponse
     */
    public function getListRegion(StationRepository $stationRepository)
    {
        $data = $stationRepository->findAllRegion();

        return new JsonResponse(['data' => array_values(array_unique($data, SORT_REGULAR))]);
    }

    /**
     * @Route("/by-region",name="region_list_station")
     *
     * @param StationRepository $stationRepository
     * @param Request           $request
     *
     * @return JsonResponse
     */
    public function getListStation(StationRepository $stationRepository, Request $request)
    {
        $regions = json_decode($request->getContent(), true);
        $data = $stationRepository->findByRegion('Analamanga');
        if (isset($regions['region']) && $regions['region'] !== '' && $regions['region']) {
            $data = $stationRepository->findByRegion($regions['region']);
        }

        return new JsonResponse(['data' => $this->handleList($data)]);
    }

    /**
     * @Route("/search/station",name="station_search")
     *
     * @param StationRepository $stationRepository
     * @param Request           $request
     *
     * @return JsonResponse
     */
    public function search(StationRepository $stationRepository, Request $request)
    {
        $search = json_decode($request->getContent(), true);

        $searchValue = $search['search'] ?? '';
        $region = $search['region'] ?? 'Analamanga';
        $limit = $search['limit'] ?? 10;

        $data = $stationRepository->search($searchValue, $region, $limit);

        return new JsonResponse(['data' => $this->handleList($data)]);
    }

    /**
     * @param mixed $stations
     *
     * @return array
     */
    public function handleList($stations = null)
    {
        $list = [];
        foreach ($stations as $key => $station) {
            $list[$key]['name'] = $station->getNomStation();
            $list[$key]['distributeur'] = $station->getDistributeur();
            $list[$key]['localite'] = $station->getLocalites();
        }

        return array_values(array_unique($list, SORT_REGULAR));
    }
}
