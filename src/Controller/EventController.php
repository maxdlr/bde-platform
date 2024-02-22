<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Repository\EventRepository;
use App\Repository\ParticipantRepository;
use Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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

    #[Route('/events/delete', name: 'app_events_new', httpMethod: ['GET'])]
    public function delete(): string
    {

    }


    /*#[Route('/events/{id}', name: 'app_event_detail', httpMethod: ['GET'])]
    public function detail(int $idEvent, EventRepository $eventRepository): string
    {

        if($eventRepository->findOneBy(['id' => $idEvent])) {
            return $this->twig->render('event/detail.html.twig', [
                'event' => $eventRepository->findOneBy(['id' => $idEvent])
            ]);
        } else {
            return $this->twig->render('404.html.twig');
        }
    }*/

}