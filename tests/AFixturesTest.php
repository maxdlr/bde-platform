<?php

use App\Factory\EventFactory;
use App\Factory\InterestedFactory;
use App\Factory\ParticipantFactory;
use App\Factory\UserFactory;
use App\Repository\EventRepository;
use App\Repository\InterestedRepository;
use App\Repository\ParticipantRepository;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertIsArray;

class AFixturesTest extends TestCase
{
    public function testCreateUsers()
    {
        $userRepository = new UserRepository();
        $users = UserFactory::make(10)->generate();
        foreach ($users as $user) {
            $userRepository->insertOne($user);
        }

        assertIsArray($users);
    }

    public function testCreateEvents()
    {
        $eventRepository = new EventRepository();
        $events = EventFactory::make(20)->generate();
        foreach ($events as $event) {
            $eventRepository->insertOne($event);
        }

        assertIsArray($events);
    }

    public function testCreateInterested()
    {
        $interestedRepository = new InterestedRepository();
        $interesteds = InterestedFactory::make(30)->generate();
        foreach ($interesteds as $interested) {
            $interestedRepository->insertOne($interested);
        }

        assertIsArray($interesteds);
    }

    public function testCreateParticipant()
    {
        $participantRepository = new ParticipantRepository();
        $participants = ParticipantFactory::make(50)->generate();
        foreach ($participants as $participant) {
            $participantRepository->insertOne($participant);
        }

        assertIsArray($participants);
    }
}