<?php
/**
 * Julien Rajerison <julienrajerison5@gmail.com>
 **/

namespace App\Controller\Offre;

use App\Controller\AbstractBaseController;
use App\Entity\Offre;
use App\Manager\OffreManager;
use App\Repository\OffreRepository;
use App\Repository\UserRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OffreController.
 *
 * @Route("/api/offre")
 */
class OffreController extends AbstractBaseController
{
    /** @var OffreRepository */
    private $offreRepository;

    /** @var UserRepository */
    private $userRepos;

    /** @var OffreManager */
    private $offreManager;

    /**
     * OffreController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param SerializerUtils        $serializerUtils
     * @param OffreRepository        $offreRepository
     * @param UserRepository         $userRepository
     * @param OffreManager           $offreManager
     */
    public function __construct(EntityManagerInterface $entityManager, SerializerUtils $serializerUtils, OffreRepository $offreRepository, UserRepository $userRepository, OffreManager $offreManager)
    {
        parent::__construct($entityManager, $serializerUtils);
        $this->offreRepository = $offreRepository;
        $this->userRepos = $userRepository;
        $this->offreManager = $offreManager;
    }

    /**
     * @param Request $request
     *
     * @Route("/manage",name="add_offre")
     *
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function addNewOffre(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $offre = $this->offreManager->handleOffre($data);

        if ($this->save($offre)) {
            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error']);
    }

    /**
     * @Route("/list",name="list_offre")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getListOffre(Request $request)
    {
        $limit = $request->get('limit');
        $data = $this->offreRepository->findPaginated($limit + 10);
        $lists = [];

        foreach ($data as $key => $offre) {
            $lists[$key]['id'] = $offre->getId();
            $lists[$key]['user'] = $offre->getUser() ? $offre->getUser()->getName() : 'Signaleo';
            $lists[$key]['depart'] = $offre->getDepart();
            $lists[$key]['arrive'] = $offre->getArrive();
            $lists[$key]['nombreDePlace'] = $offre->getNombreDePlace();
            $lists[$key]['frais'] = $offre->getFrais();
            $lists[$key]['contact'] = $offre->getContact() ?? 'Signaleo';
            $lists[$key]['dateDepart'] = $offre->getDateDepart() ? $offre->getDateDepart()->format('d-m-Y H:i') : 'Androany';
        }

        return new JsonResponse(['status' => 200, 'message' => $lists]);
    }

    /**
     * @Route("/delete/{id}",name="delete_zambaento")
     *
     * @param Offre $offre
     *
     * @return JsonResponse
     */
    public function remove(Offre $offre)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        if ($this->delete($offre)) {
            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error']);
    }
}