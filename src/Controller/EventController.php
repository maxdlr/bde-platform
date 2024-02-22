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
    public function show(int $id): string
    {
        $event = $this->eventRepository->findOneBy(['id' => $id]);
        $remainingCapacity = $event->getCapacity() - count($this->participantRepository->findBy(['event_id' => $event->getId()]));

        return $this->twig->render('event/show.html.twig', [
            'event' => $event,
            'remainingCapacity' => $remainingCapacity,
        ]);
    }
}