<?php
/**
 * @author <Bocasay>.
 */

namespace App\Controller\Societe;

use App\Controller\AbstractBaseController;
use App\Entity\Societe;
use App\Repository\SocieteRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/societe")
 * Class SocieteController.
 */
class SocieteController extends AbstractBaseController
{
    /** @var SocieteRepository */
    private $societeRepository;

    /**
     * SocieteController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param SerializerUtils        $serializerUtils
     * @param SocieteRepository      $societeRepository
     */
    public function __construct(EntityManagerInterface $entityManager, SerializerUtils $serializerUtils, SocieteRepository $societeRepository)
    {
        parent::__construct($entityManager, $serializerUtils);
        $this->societeRepository = $societeRepository;
    }

    /**
     * @Route("/list",name="societe_list")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getSociete(Request $request)
    {
        try {
            $limit = $request->get('limit');
            $search = $request->get('search');
            $data = $this->societeRepository->findByName($search, $limit);

            $list = [];
            foreach ($data as $key => $item) {
                $list[$key]['type'] = $item->getType();
                $list[$key]['nom'] = $item->getNom();
                $list[$key]['adresse'] = $item->getAdresse();
                $list[$key]['responsable'] = $item->getResponsable();
            }

            return new JsonResponse(['message' => 'success', 'data' => $data]);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => 'error']);
        }
    }

    /**
     * @Route("/add",name="societe_add")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addSociete(Request $request)
    {
        $societe = new Societe();
        $societe
            ->setType($request->get('type'))
            ->setNom($request->get('nom'))
            ->setAdresse($request->get('adress'))
            ->setContact($request->get('contact'))
            ->setResponsable($request->get('responsable'));

        if ($this->save($societe)) {
            return new JsonResponse(['message' => 'success']);
        }

        return new JsonResponse(['message' => 'error']);
    }
}