<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Entity\Participant;
use App\Repository\EventRepository;
use App\Repository\ParticipantRepository;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use App\Service\AlertManager;
class IndexController extends AbstractController
{
    public function __construct(
        Environment                            $twig,
        private readonly EventRepository       $eventRepository,
        private readonly ParticipantRepository $participantRepository
    )
    {
        parent::__construct($twig);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Route('/', name: 'app_home', httpMethod: ['GET'])]
    public function home(): string
    {
        $alertManager = new AlertManager;
        $alertManager->alert();
        $events = $this->eventRepository->findAll();
        $capacities = [];
        foreach ($events as $event) {
            $capacities[] = [$event->getId(), $event->getCapacity()];
            $event->setCapacity($event->getCapacity() - count($this->participantRepository->findBy(['event_id' => $event->getId()])));

            $event->setDescription($this->truncate($event->getDescription(), 200, '...'));
        }

        return $this->twig->render('index/home.html.twig', [
            'events' => $events,
            'capacities' => $capacities,
        ]);
    }
}