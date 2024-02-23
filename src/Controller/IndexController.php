<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\ParticipantRepository;
use App\Service\EventFilter;
use DateTime;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use App\Service\AlertManager;

class IndexController extends AbstractController
{
    private User|null|bool $currentUser;

    public function __construct(
        Environment                            $twig,
        private readonly EventRepository       $eventRepository,
        private readonly ParticipantRepository $participantRepository
    )
    {
        parent::__construct($twig);
        $this->currentUser = $this->getUserConnected();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws \Exception
     */
    #[Route('/', name: 'app_home', httpMethod: ['GET', 'POST'])]
    public function home(): string
    {
        $this->clearFlashs();
        $alertManager = new AlertManager;
        $eventFilter = new EventFilter();
        $alertManager->alert();
        $events = $this->eventRepository->findAll();
        $title = 'Les prochains évenements !';


        if (isset($_POST['tagfilter']) && $_POST['tagfilter'] == 'tagfilter') {
            $filteredEvents = [];
            foreach ($events as $event) {
                if ($eventFilter->isOnTag($event, $_POST['tag'])) {
                    $filteredEvents[] = $event;
                }
            }
            $events = $filteredEvents;
            $title = 'Les évenements ' . strtoupper($_POST['tag']);
        }

        if (isset($_POST['datefilter']) && $_POST['datefilter'] == 'datefilter') {
            $startDate = new DateTime($_POST['startDate']);
            $endDate = new DateTime($_POST['endDate']);
            $filteredEvents = [];
            foreach ($events as $event) {
                if ($eventFilter->isEventBetween($event, $startDate, $endDate)) {
                    $filteredEvents[] = $event;
                }
            }
            $events = $filteredEvents;
            $title = 'Les évenements entre le ' . $startDate->format('d-m-Y') . ' et le ' . $endDate->format('d-m-Y');
        }

        $firstEvent = EventFilter::use($events)->sortBy('date')->return()[0];
        $lastEvent = EventFilter::use($events)->sortBy('date', true)->return()[0];
        
        $capacities = [];
        foreach ($events as $event) {
            $capacities[] = [$event->getId(), $event->getCapacity()];
            $event->setCapacity($event->getCapacity() - count($this->participantRepository->findBy(['event_id' => $event->getId()])));
            $event->setDescription($this->truncate($event->getDescription(), 200, '...'));
        }

        return $this->twig->render('index/home.html.twig', [
            'events' => $events,
            'capacities' => $capacities,
            'currentUser' => $this->currentUser,
            'flashbag' => $_SESSION["flashbag"],
            'title' => $title,
            'firstEvent' => $firstEvent,
            'endEvent' => $lastEvent,
        ]);
    }
}