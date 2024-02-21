<?php

use App\Entity\Event;
use App\Entity\User;
use App\Entity\Interested;
use App\Factory\EventFactory;
use App\Factory\InterestedFactory;
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
        $faker = Factory::create();

        $events = $eventRepository->findAll();
        $event = $faker->randomElement($events);

        $users = $userRepository->findAll();
        $user = $faker->randomElement($users);

        $interested = InterestedFactory::make()->withUser($user)->withEvent($event)->generate();
        $interestedRepository->insertOne($interested);

        $interestedObject = $interestedRepository->findOneBy(['event_id' => $event->getId()]);

        self::assertInstanceOf(Interested::class, $interestedObject);

        $interestedRepository->delete($interested);
    }
}