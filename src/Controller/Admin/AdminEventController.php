<?php

namespace App\Controller\Admin;

use App\Attribute\Route;
use App\Controller\AbstractController;
use App\Entity\Event;
use App\Enum\TagEnum;
use App\Mapping\Event\EventOTD;
use App\Repository\EventRepository;
use App\Repository\TagRepository;
use DateTime;
use Twig\Environment;

class AdminEventController extends AbstractController
{
    public function __construct(
        Environment                      $twig,
        private readonly EventRepository $eventRepository,
        private readonly TagRepository   $tagRepository,
    )
    {
        parent::__construct($twig);
    }

    #[Route('/admin/event/index', name: 'app_admin_event_index', httpMethod: ['GET'])]
    public function index(): string
    {
        $eventObjects = $this->eventRepository->findAll();
        $events = array_map(fn(Event $event): array => $event->toArray(), $eventObjects);

        return $this->twig->render('admin/index.html.twig', [
            'items' => $events,
            'entityName' => 'event'
        ]);
    }

    #[Route('/admin/event/new', name: 'app_event_new', httpMethod: ['GET', 'POST'])]
    public function new(): string
    {
        if (isset($_POST['edit-event-submit']) && $_POST['edit-event-submit'] == 'new-event') {
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
                $this->redirect('/admin/event/index');
            }
        }

        $tags = $this->tagRepository->findAll();
        return $this->twig->render('admin/new/event-new.html.twig', [
            'tags' => $tags,
        ]);
    }

    #[Route('/admin/event/edit', name: 'app_admin_event_edit', httpMethod: ['GET', 'POST'])]
    public function edit(): string
    {
        if (isset($_POST['edit-event-submit']) && $_POST['edit-event-submit'] == 'edit-event') {
            $eventRepository = new EventRepository();
            $event = $eventRepository->findOneBy(['id' => $_POST['edit-event-input']]);
        }
        $tags = $this->tagRepository->findAll();

        return $this->twig->render('admin/edit/event-edit.html.twig', [
            'item' => $event,
            'tags' => $tags,
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