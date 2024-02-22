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
    public function show(int $idEvent): string
    {
        $eventRepository = new EventRepository();
        $eventToShow = $eventRepository->findOneBy(['id' => $idEvent]);

        if(!is_null($eventToShow)){
            return $this->twig->render('event/show.html.twig', [
                'event' => $eventToShow,
            ]);
        } else {
            return $this->twig->render('404.html.twig');
        }
    }

}