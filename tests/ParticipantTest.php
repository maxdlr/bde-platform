<?php

use App\Entity\Event;
use App\Entity\Interested;
use App\Entity\User;
use App\Entity\Participant;
use App\Factory\EventFactory;
use App\Factory\UserFactory;
use App\Repository\EventRepository;
use App\Repository\InterestedRepository;
use App\Repository\UserRepository;
use App\Repository\ParticipantRepository;
use App\Mapping\Participant\ParticipantDTO;
use App\Mapping\Participant\ParticipantOTD;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class ParticipantTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCanProcessParticipantObject()
    {
        $eventRepository = new EventRepository();
        $userRepository = new UserRepository();
        $participantRepository = new ParticipantRepository();

        $event = EventFactory::make()->generate();
        $eventRepository->insertOne($event);
        $eventObject = $eventRepository->findOneBy(['name' => $event->getName()]);

        $user = UserFactory::make()->generate();
        $userRepository->insertOne($user);
        $userObject = $userRepository->findOneBy(['firstname' => $user->getFirstname()]);

        $participant = new Participant();
        $participant
            ->setEventId($eventObject->getId())
            ->setUserId($userObject->getId());

        $participantRepository->insertOne($participant);

        self::assertNotNull($participantRepository);

        $participantObject = $participantRepository->findOneBy(['event_id' => $eventObject->getId()]);

        self::assertInstanceOf(Participant::class, $participantObject);

        $participantRepository->delete($participant);
    }
}