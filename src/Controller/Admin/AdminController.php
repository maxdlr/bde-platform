<?php

namespace App\Controller\Admin;

use App\Attribute\Route;
use App\Controller\AbstractController;
use App\Repository\EventRepository;
use App\Repository\InterestedRepository;
use App\Repository\ParticipantRepository;
use App\Repository\UserRepository;
use App\Service\EventFilter;
use Twig\Environment;

class AdminController extends AbstractController
{
    public function __construct(
        Environment                            $twig,
        private readonly UserRepository        $userRepository,
        private readonly EventRepository       $eventRepository,
        private readonly ParticipantRepository $participantRepository,
        private readonly InterestedRepository  $interestedRepository,
    )
    {
        parent::__construct($twig);
    }

    #[Route('/admin/dashboard', name: 'app_admin_dashboard', httpMethod: ['GET'])]
    public function dashboard(): string
    {
        $eventFilter = new EventFilter();
        $users = $this->userRepository->findAll();
        $participants = $this->participantRepository->findAll();
        $interesteds = $this->interestedRepository->findAll();
        $events = $this->eventRepository->findAll();

        $futureEvents = [];
        foreach ($events as $event) {
            if ($eventFilter->isEventDateGreaterThan($event, new \DateTime('now'))) {
                $futureEvents[] = $event;
            }
        }

        return $this->twig->render('admin/dashboard/index.html.twig', [
            'users' => $users,
            'events' => $events,
            'interesteds' => $interesteds,
            'participants' => $participants,
            'futureEvents' => $futureEvents
        ]);
    }
}