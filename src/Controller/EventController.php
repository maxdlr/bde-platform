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
        private readonly TagRepository   $tagRepository,
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

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    #[Route('/event/new', name: 'app_event_new', httpMethod: ['GET', 'POST'])]
    public function new(): string
    {
        if (isset($_POST['eventSubmit']) && $_POST['eventSubmit'] == 'new-event') {
            $event = new Event();
            $eventRepository = new EventRepository();

            array_map('trim', $_POST);

            $event
                ->setName($_POST['name'])
                ->setDescription($_POST['description'])
                ->setStartDate(new DateTime($_POST['startDate']))
                ->setEndDate(new DateTime($_POST['endDate']))
                ->setTag($_POST['tag'])
                ->setCapacity($_POST['capacity'])
                ->setOwnerId(1);

            if ($eventRepository->insertOne($event)) {
                $this->redirect('/events');
            }
        }

        $tags = $this->tagRepository->findAll();
        return $this->twig->render('event/new.html.twig', [
            'tags' => $tags,
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

    #[Route('/admin/event/delete', name: 'app_event_delete', httpMethod: ['GET', 'POST'])]
    public function delete(): void
    {
        if (isset($_POST['deleteSubmit']) && $_POST['deleteSubmit'] == 'delete-event') {
            $eventRepository = new EventRepository();
            $eventToDelete = $eventRepository->findOneBy(['id' => $_POST['idDelete']]);

            if ($eventRepository->delete($eventToDelete)) {
                $this->redirect('/admin/event/index');
            }
        }
    }
}