<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Entity\Interested;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\InterestedRepository;
use App\Repository\UserRepository;
use Twig\Environment;


class InterestedController extends AbstractController
{
    private User|bool $currentUser;

    public function __construct(
        Environment                           $twig,
        private readonly EventRepository      $eventRepository,
        private readonly InterestedRepository $interestedRepository
    )
    {
        parent::__construct($twig);
        $this->currentUser = $this->getUserConnected();
    }


    #[Route('/event/new/interested/{id}', name: 'app_interested_new', httpMethod: ['GET'])]
    public function newInterested(int $idEvent): string
    {
        $event = $this->eventRepository->findOneBy(['id' => $idEvent]);

        $interested = new Interested();
        $interestedRepository = new InterestedRepository();

        $interested
            ->setEventId($event->getId())
            ->setUserId($this->currentUser->getId());

        if ($interestedRepository->insertOne($interested)) {
            $this->redirect('/event/show/' . $idEvent);
        }
    }

    #[Route('/event/delete/interested/{id}', name: 'app_interested_delete', httpMethod: ['GET'])]
    public function deleteInterested(int $idEvent): string
    {
        $interestedList = $this->interestedRepository->findBy(['event_id' => $idEvent]);

        foreach ($interestedList as $participant) {
            if ($participant->getUserId() == $this->currentUser->getId()) {
                $this->interestedRepository->delete($participant);
            }
        }

        $this->redirect('/event/show/' . $idEvent);
    }
}