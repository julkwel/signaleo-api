<?php
/**
 * @author RAJERISON Julien <julienrajerison@gmail.com>.
 */

namespace App\Manager;

use App\Entity\Participants;
use App\Repository\ParticipantsRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Manager.
 */
class ParticipantManager
{
    /** @var ParticipantsRepository */
    private $repository;

    /**
     * ParticipantManager constructor.
     *
     * @param ParticipantsRepository $repository
     */
    public function __construct(ParticipantsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Create participants object
     *
     * @param Request $request
     *
     * @return Participants
     */
    public function manageParticipants(Request $request)
    {
        $dataFromRequest = json_decode($request->getContent(), true);

        $participant = $this->repository->find($dataFromRequest['id']);
        $participant = $participant ?? new Participants();
        $participant->setName($dataFromRequest['firstName']);
        $participant->setLastName($dataFromRequest['lastname']);
        $participant->setGender($dataFromRequest['gender']);

        return $participant;
    }

    /**
     * @param ParticipantsRepository $repository
     *
     * @return array
     */
    public function getParticipants(ParticipantsRepository $repository)
    {
        $list = $repository->findBy([], ['id' => 'desc']);

        $lists = [];
        foreach ($list as $key => $participants) {
            $lists[$key]['id'] = $participants->getId();
            $lists[$key]['lastname'] = $participants->getLastName();
            $lists[$key]['firstName'] = $participants->getName();
            $lists[$key]['gender'] = $participants->getGender();
        }

        return $lists;
    }
}