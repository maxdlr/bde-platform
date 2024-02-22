<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\InterestedRepository;
use App\Repository\ParticipantRepository;
use Twig\Environment;

class EventController extends AbstractController
{
    private User|bool $currentUser;

    public function __construct(
        Environment                            $twig,
        private readonly EventRepository       $eventRepository,
        private readonly ParticipantRepository $participantRepository
    )
    {
        parent::__construct($twig);
        $this->currentUser = $this->getUserConnected();
    }

    #[Route('/event/show/{id}', name: 'app_event_show', httpMethod: ['POST'])]
    public function show(int $idEvent): string
    {
        $event = $this->eventRepository->findOneBy(['id' => $idEvent]);
        $remainingCapacity = $event->getCapacity() - count($this->participantRepository->findBy(['event_id' => $event->getId()]));

        if ($this->currentUser) {

            $userParticipant = false;
            $userInterested = false;

            $participantRepository = new ParticipantRepository();
            $participantList = $participantRepository->findBy(['event_id' => $idEvent]);
            foreach ($participantList as $participant) {
                if ($participant->getUserId() == $this->currentUser->getId()) {
                    $userParticipant = true;
                }
            }

            $interestedRepository = new InterestedRepository();
            $interestedList = $interestedRepository->findBy(['event_id' => $idEvent]);
            foreach ($interestedList as $interested) {
                if ($interested->getUserId() == $this->currentUser->getId()) {
                    $userInterested = true;
                }
            }
        }

        return $this->twig->render('event/show.html.twig', [
            'event' => $event,
            'remainingCapacity' => $remainingCapacity,
            'connectedUser' => $this->currentUser,
            'userParticipant' => $userParticipant,
            'userInterested' => $userInterested
        ]);
    }
}