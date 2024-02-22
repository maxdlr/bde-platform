<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\ParticipantRepository;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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
        $this->clearFlashs();

        $events = $this->eventRepository->findAll();
        $capacities = [];
        foreach ($events as $event) {
            $capacities[] = [$event->getId(), $event->getCapacity()];
            $event->setCapacity($event->getCapacity() - count($this->participantRepository->findBy(['event_id' => $event->getId()])));
        }

        if(!is_null($_SESSION["user_connected"])){
            $connectedUser = $this->getUserConnected();
            $this->addFlash("success", "Vous êtes bien connecté !");
        } else {
            $connectedUser = null;
        }

        return $this->twig->render('index/home.html.twig', [
            'events' => $events,
            'capacities' => $capacities,
            'connectedUser' => $connectedUser,
            'flashbag' => $_SESSION["flashbag"]
        ]);
    }
}