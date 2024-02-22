<?php

namespace App\Controller\Admin;

use App\Attribute\Route;
use App\Controller\AbstractController;
use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use DateTime;
use Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminEventController extends AbstractController
{
    public function __construct(
        Environment                      $twig,
        private readonly EventRepository $eventRepository,
        private readonly TagRepository   $tagRepository,
        private readonly UserRepository  $userRepository,
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
    #[Route('/admin/event/index', name: 'app_admin_event_index', httpMethod: ['GET', 'POST'])]
    public function index(): string
    {
        $eventObjects = $this->eventRepository->findAll();
        $events = array_map(fn(Event $event): array => $event->toArray(), $eventObjects);

        $eventsWithOwners = [];
        foreach ($events as $event) {

            $eventsWithOwners[] = [
                ...$event,
                'owner' => $this->userRepository->findOneBy(
                        ['id' => $event['owner_id']]
                    )->getFirstname()
                    . ' '
                    . $this->userRepository->findOneBy(
                        ['id' => $event['owner_id']]
                    )->getLastname()
            ];
        }

        return $this->twig->render('admin/index.html.twig', [
            'items' => $eventsWithOwners,
            'entityName' => 'event'
        ]);
    }

    #[Route('/admin/event/new', name: 'app_admin_event_new', httpMethod: ['GET', 'POST'])]
    public function new(): string
    {
        if (isset($_POST['new-event-submit']) && $_POST['new-event-submit'] == 'new-event') {
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

    #[Route('/admin/event/edit/{id}', name: 'app_admin_event_edit', httpMethod: ['GET', 'POST'])]
    public function edit(int $idEvent): string
    {
        $eventRepository = new EventRepository();
        $event = $eventRepository->findOneBy(['id' => $idEvent]);

        // Case when the edit form was submitted
        if (isset($_POST['edit-event-submit']) && $_POST['edit-event-submit'] == 'edit-event') {

            $updatedEventArray = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'startDate' => $_POST['startDate'],
                'endDate' => $_POST['endDate'],
                'tag' => $_POST['tag'],
                'capacity' => $_POST['capacity']
            ];

            if ($eventRepository->update($updatedEventArray, $event)) {
                $this->redirect('/admin/event/index');
            }
        } elseif (!is_null($event)) {
            $tags = $this->tagRepository->findAll();
            return $this->twig->render('admin/edit/event-edit.html.twig', [
                'item' => $event,
                'tags' => $tags,
            ]);
        } else {
            return $this->twig->render('404.html.twig');
        }
    }

    #[Route('/admin/event/delete/{id}', name: 'app_admin_event_delete', httpMethod: ['POST'])]
    public function delete(int $idEvent): void
    {
        $eventRepository = new EventRepository();
        $eventToDelete = $eventRepository->findOneBy(['id' => $idEvent]);

        if ($eventRepository->delete($eventToDelete)) {
            $this->redirect('/admin/event/index');
        }
    }
}