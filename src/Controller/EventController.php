<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Repository\EventRepository;
use App\Repository\ParticipantRepository;
use Twig\Environment;

class EventController extends AbstractController
{
    public function __construct(
        Environment                            $twig,
        private readonly EventRepository       $eventRepository,
        private readonly ParticipantRepository $participantRepository
    )
    {
        parent::__construct($twig);
    }

    #[Route('/event/show/{id}', name: 'app_event_show', httpMethod: ['POST'])]
    public function show(int $idEvent): string
    {
        $event = $this->eventRepository->findOneBy(['id' => $idEvent]);
        $remainingCapacity = $event->getCapacity() - count($this->participantRepository->findBy(['event_id' => $event->getId()]));

        if(!is_null($_SESSION["user_connected"])){
            $connectedUser = $this->getUserConnected();

            $participantRepository = new ParticipantRepository();
            $participantList = $participantRepository->findBy(['event_id' => $idEvent]);
            foreach ($participantList as $participant){
                if ($participant->getUserId() == $connectedUser->getId()){
                    $userParticipant = true;
                } else {
                    $userParticipant = false;
                }
            }

            $this->addFlash("success", "Vous êtes bien connecté !");
        } else {
            $connectedUser = null;
        }

        return $this->twig->render('event/show.html.twig', [
            'event' => $event,
            'remainingCapacity' => $remainingCapacity,
            'connectedUser' => $connectedUser,
            'userParticipant' => $userParticipant
        ]);
    }
}