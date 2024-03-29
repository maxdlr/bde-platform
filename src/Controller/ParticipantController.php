<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Repository\ParticipantRepository;
use App\Service\Mail\MailManager;
use Twig\Environment;


class ParticipantController extends AbstractController
{
    private User|null|bool $currentUser;

    public function __construct(
        Environment                            $twig,
        private readonly EventRepository       $eventRepository,
        private readonly UserRepository        $userRepository,
        private readonly ParticipantRepository $participantRepository
    )
    {
        parent::__construct($twig);
        $this->currentUser = $this->getUserConnected();
        $this->redirectIfForbidden();
    }


    #[Route('/event/new/participant/{id}', name: 'app_participant_new', httpMethod: ['GET'])]
    public function newParticipant(int $idEvent): ?string
    {
        $this->clearFlashs();

        $event = $this->eventRepository->findOneBy(['id' => $idEvent]);

        $participant = new Participant();
        $participantRepository = new ParticipantRepository();
        $user = $this->userRepository->findOneBy(['id' => $this->currentUser->getId()]);

        $listParticipant = $participantRepository->findBy(["event_id" => $idEvent]);
        if (!is_null($listParticipant) && sizeof($listParticipant) >= $event->getCapacity()) {
            $this->addFlash("danger", "Vous ne pouvez pas vous inscrire à cet évènement : il n'y a plus de place !");
            $this->redirect('/');
        } else {
            $mailManager = new MailManager;

            $participant
                ->setEventId($event->getId())
                ->setUserId($this->currentUser->getId());

            if ($participantRepository->insertOne($participant)) {
                $mailManager->sendMailParticipant($user, $event);
                $this->redirect('/event/show/' . $idEvent);
            }
        }
    }

    #[Route('/event/delete/participant/{id}', name: 'app_participant_delete', httpMethod: ['GET'])]
    public function deleteParticipant(int $idEvent): void
    {
        $participantList = $this->participantRepository->findBy(['event_id' => $idEvent]);

        foreach ($participantList as $participant) {
            if ($participant->getUserId() == $this->currentUser->getId()) {
                $this->participantRepository->delete($participant);
            }
        }

        $this->redirect('/event/show/' . $idEvent);
    }
}