<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Entity\Event;
use App\Enum\TagEnum;
use App\Mapping\Event\EventOTD;
use App\Repository\EventRepository;
use App\Repository\TagRepository;
use DateTime;
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

    #[Route('/event/show', name: 'app_event_show', httpMethod: ['POST'])]
    public function show(): string
    {
        if (isset($_POST['showSubmit']) && $_POST['showSubmit'] == 'showEvent') {
            $eventRepository = new EventRepository();
            $event = $eventRepository->findOneBy(['id' => $_POST['idShow']]);
        }

        return $this->twig->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }
}