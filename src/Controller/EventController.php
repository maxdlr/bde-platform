<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Repository\EventRepository;
use Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EventController extends AbstractController
{
    public function __construct(
        Environment                      $twig,
        private readonly EventRepository $eventRepository,
    )
    {
        parent::__construct($twig);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    #[Route('/events', name: 'app_event_index', httpMethod: ['GET'])]
    public function index(): string
    {
        $events = $this->eventRepository->findAll();

        return $this->twig->render('event/index.html.twig', [
            'events' => $events,
        ]);
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