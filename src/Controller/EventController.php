<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Repository\EventRepository;
use Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use App\Service\EventFilter;

class EventController extends AbstractController
{
    public function __construct(
        Environment                      $twig,
        private readonly EventRepository $eventRepository
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
    #[Route('/events', name: 'app_events_index', httpMethod: ['GET'])]
    public function index(): string
    {
        $events = $this->eventRepository->findAll();
        $events = EventFilter::use($events)->sortBy("date")->return();
        return $this->twig->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Route('/events/new', name: 'app_events_new', httpMethod: ['GET'])]
    public function new(): string
    {
        return $this->twig->render('event/new.html.twig');
    }

    #[Route('/events/edit', name: 'app_events_new', httpMethod: ['GET'])]
    public function edit(): string
    {

    }

    #[Route('/events/delete', name: 'app_events_new', httpMethod: ['GET'])]
    public function delete(): string
    {

    }

}