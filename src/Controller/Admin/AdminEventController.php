<?php

namespace App\Controller\Admin;

use App\Attribute\Route;
use App\Controller\AbstractController;
use App\Entity\Event;
use App\Mapping\Event\EventOTD;
use App\Repository\EventRepository;
use Twig\Environment;

class AdminEventController extends AbstractController
{
    public function __construct(
        Environment                      $twig,
        private readonly EventRepository $eventRepository,
    )
    {
        parent::__construct($twig);
    }

    #[Route('/admin/event/index', name: 'app_admin_event_edit', httpMethod: ['GET'])]
    public function index(): string
    {
        $eventOTD = new EventOTD();
        $eventObjects = $this->eventRepository->findAll();
        $events = array_map(fn(Event $event): array => $eventOTD->config($event)->process(), $eventObjects);

        return $this->twig->render('admin/index.html.twig', [
            'items' => $events,
            'entityName' => 'event'
        ]);
    }

    #[Route('/admin/event/edit', name: 'app_admin_event_edit', httpMethod: ['GET', 'POST'])]
    public function edit(): string
    {
        if (isset($_POST['editSubmit']) && $_POST['editSubmit'] == 'edit-event') {
            $eventRepository = new EventRepository();
            $event = $eventRepository->findOneBy(['id' => $_POST['idShow']]);
        }

        return $this->twig->render('admin/event/edit.html.twig');
    }
}