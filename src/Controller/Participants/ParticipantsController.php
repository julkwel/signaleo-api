<?php
/**
 * @author RAJERISON Julien <julienrajerison@gmail.com>.
 */

namespace App\Controller\Participants;

use App\Controller\AbstractBaseController;
use App\Entity\Participants;
use App\Manager\ParticipantManager;
use App\Repository\ParticipantsRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PartageController.
 */
class ParticipantsController extends AbstractBaseController
{
    /** @var ParticipantManager */
    private $participantsManager;

    /**
     * ParticipantsController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param SerializerUtils        $serializerUtils
     * @param ParticipantManager     $participantManager
     */
    public function __construct(EntityManagerInterface $entityManager, SerializerUtils $serializerUtils, ParticipantManager $participantManager)
    {
        parent::__construct($entityManager, $serializerUtils);

        $this->participantsManager = $participantManager;
    }

    /**
     * @Route("/{reactRoute?}", name="home_page",methods={"GET"})
     *
     * @return Response
     */
    public function homePage()
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/list/participants", name="list_participants",methods={"GET"})
     *
     * @param ParticipantsRepository $repository
     *
     * @return Response
     */
    public function listParticipants(ParticipantsRepository $repository)
    {
        $list = $this->participantsManager->getParticipants($repository);

        return new JsonResponse(['list' => $list]);
    }

    /**
     * @Route("/new/participant", name="new_participant",methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function newParticipant(Request $request)
    {
        $participant = $this->participantsManager->manageParticipants($request);
        if ($this->save($participant)) {
            return new JsonResponse(['status' => 'success', 'participant' => $participant->getId()]);
        }

        return new JsonResponse(['status' => 'error']);
    }

    /**
     * @Route("/delete/participant/{id}", name="delete_participant",methods={"GET","POST"})
     *
     * @param Participants $participants
     *
     * @return JsonResponse
     */
    public function removeParticipant(Participants $participants)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        if ($this->delete($participants)) {
            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error']);
    }
}