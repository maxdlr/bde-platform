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
    #[Route('/events', name: 'app_events_index', httpMethod: ['GET'])]
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
    #[Route('/events/new', name: 'app_events_new', httpMethod: ['GET', 'POST'])]
    public function new(): string
    {
        if (isset($_POST['eventSubmit']) && $_POST['eventSubmit'] == 'newEvent') {
            $event = new Event();
            $eventOtd = new EventOTD();
            $eventRepository = new EventRepository();

            $event
                ->setName($_POST['name'])
                ->setDescription($_POST['description'])
                ->setStartDate(new DateTime($_POST['startDate']))
                ->setEndDate(new DateTime($_POST['endDate']))
                ->setTag($_POST['tag'])
                ->setCapacity($_POST['capacity'])
                ->setOwnerId(1);

            if ($eventRepository->insertOne($eventOtd->config($event)->process())) {
                $this->redirect('/events');
            }
        }

        $tags = $this->tagRepository->findAll();
        return $this->twig->render('event/new.html.twig', [
            'tags' => $tags,
        ]);
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