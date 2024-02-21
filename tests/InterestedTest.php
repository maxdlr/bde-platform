<?php

use App\Entity\Event;
use App\Entity\User;
use App\Entity\Interested;
use App\Factory\EventFactory;
use App\Factory\UserFactory;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Repository\InterestedRepository;
use App\Mapping\Interested\InterestedOTD;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class InterestedTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCanProcessInterestedObject()
    {
        $eventRepository = new EventRepository();
        $userRepository = new UserRepository();
        $interestedRepository = new InterestedRepository();

        $event = EventFactory::make()->generate();
        $eventRepository->insertOne($event);
        $eventObject = $eventRepository->findOneBy(['name' => $event->getName()]);

        $user = UserFactory::make()->generate();
        $userRepository->insertOne($user);
        $userObject = $userRepository->findOneBy(['firstname' => $user->getFirstname()]);

        $interested = new Interested();
        $interested
            ->setEventId($eventObject->getId())
            ->setUserId($userObject->getId());

        $interestedRepository->insertOne($interested);

        self::assertNotNull($interestedRepository);

        $interestedObject = $interestedRepository->findOneBy(['event_id' => $eventObject->getId()]);

        self::assertInstanceOf(Interested::class, $interestedObject);

        $interestedRepository->delete($interested);
    }
}