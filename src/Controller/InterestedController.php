<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Entity\Interested;
use App\Entity\Participant;
use App\Repository\EventRepository;
use App\Repository\InterestedRepository;
use App\Repository\UserRepository;
use Twig\Environment;


class InterestedController extends AbstractController
{
    public function __construct(
        Environment                            $twig,
        private readonly EventRepository       $eventRepository,
        private readonly UserRepository        $userRepository,
        private readonly InterestedRepository  $interestedRepository
    )
    {
        parent::__construct($twig);
    }


    #[Route('/event/new/interested/{id}', name: 'app_interested_new', httpMethod: ['GET'])]
    public function newInterested(int $idEvent): string
    {
        $event = $this->eventRepository->findOneBy(['id' => $idEvent]);
        $currentUser = $this->getUserConnected();

        $interested = new Interested();
        $interestedRepository = new InterestedRepository();

        $interested
            ->setEventId($event->getId())
            ->setUserId($currentUser->getId());

        if ($interestedRepository->insertOne($interested)) {
            $this->redirect('/event/show/'.$idEvent);
        }
    }

    #[Route('/event/delete/interested/{id}', name: 'app_interested_delete', httpMethod: ['GET'])]
    public function deleteInterested(int $idEvent): string
    {
        $interestedList = $this->interestedRepository->findBy(['event_id' => $idEvent]);
        $connectedUser = $this->getUserConnected();

        foreach ($interestedList as $participant){
            if ($participant->getUserId() == $connectedUser->getId()){
                $this->interestedRepository->delete($participant);
            }
        }

        $this->redirect('/event/show/'.$idEvent);
    }
}