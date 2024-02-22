<?php

use App\Enum\RoleEnum;
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
    public function testCreateMaxime()
    {
        $userRepository = new UserRepository();
        $max = UserFactory::make()
            ->withFirstname('Maxime')
            ->withLastname('de la Rocheterie')
            ->withEmail('contact@maxdlr.com')
            ->withRole(RoleEnum::ROLE_ADMIN)
            ->withSignedUpOn(new DateTime('now'))
            ->withPassword('password')
            ->withIsVerified(true)
            ->generate();

        $userRepository->insertOne($max);

        self::assertNotNull($userRepository->findOneBy(['email' => 'contact@maxdlr.com']));
    }
    public function testCreateMathieu()
    {
        $userRepository = new UserRepository();
        $mat = UserFactory::make()
            ->withFirstname('Mathieu')
            ->withLastname('Moyaerts')
            ->withEmail('mathieu.moyaerts.pro@gmail.com')
            ->withRole(RoleEnum::ROLE_ADMIN)
            ->withSignedUpOn(new DateTime('now'))
            ->withPassword('password')
            ->withIsVerified(true)
            ->generate();

        $userRepository->insertOne($mat);

        self::assertNotNull($userRepository->findOneBy(['email' => 'mathieu.moyaerts.pro@gmail.com']));
    }

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