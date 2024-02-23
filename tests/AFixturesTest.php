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

    public function testCreateLouis()
    {
        $userRepository = new UserRepository();
        $louis = UserFactory::make()
            ->withFirstname('Louis')
            ->withLastname('Cauvet')
            ->withEmail('louiscauvet8@gmail.com')
            ->withRole(RoleEnum::ROLE_ADMIN)
            ->withSignedUpOn(new DateTime('now'))
            ->withPassword('9B6rc43*>')
            ->withIsVerified(true)
            ->generate();

        $userRepository->insertOne($louis);

        self::assertNotNull($userRepository->findOneBy(['email' => 'louiscauvet8@gmail.com']));
    }

    public function testCreateUsers()
    {
        $userRepository = new UserRepository();
        $users = UserFactory::make(100)->generate();
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
        $interesteds = InterestedFactory::make(200)->generate();
        foreach ($interesteds as $interested) {
            $interestedRepository->insertOne($interested);
        }

        assertIsArray($interesteds);
    }

    public function testCreateParticipant()
    {
        $participantRepository = new ParticipantRepository();
        $participants = ParticipantFactory::make(200)->generate();
        $eventRepository = new EventRepository();


        foreach ($participants as $participant) {

            $event = $eventRepository->findOneBy(['id' => $participant->getEventId()]);

            if ($event->getCapacity() > count($participantRepository->findBy(['event_id' => $event->getId()]))) {
                $participantRepository->insertOne($participant);
            }
        }

        assertIsArray($participants);
    }

    public function testJ1()
    {
        $userRepository = new UserRepository;
        $eventRepository = new EventRepository;
        $participantRepository = new ParticipantRepository;
        $event = EventFactory::make()->withStartDate(new DateTime("tomorrow"))->withEndDate(new DateTime("+8 days"))->withName("Soirée league of legends")->generate();
        $eventRepository->insertOne($event);
        $eventtrue = $eventRepository->findOneBy(["name" => $event->getName()]);
        $moi = $userRepository->findOneBy(["email" => "mathieu.moyaerts.pro@gmail.com"]);
        $participant = ParticipantFactory::make()->withUser($moi)->withEvent($eventtrue)->generate();
        $participantRepository->insertOne($participant);

        self::assertNotNull($participantRepository->findOneBy(["user_id" => $moi->getId()]));

    }

    public function testJ5()
    {
        $eventRepository = new EventRepository;
        $userRepository = new UserRepository;
        $moi = $userRepository->findOneBy(["email" => "mathieu.moyaerts.pro@gmail.com"]);
        $event = EventFactory::make()->withStartDate(new DateTime("+5 days"))->withEndDate(new DateTime("+8 days"))->withOwner($moi)->generate();
        $eventRepository->insertOne($event);

        self::assertNotNull($eventRepository->findOneBy(["owner_id" => $moi->getId()]));
    }
}