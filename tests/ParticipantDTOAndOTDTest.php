<?php

use App\Entity\Event;
use App\Entity\User;
use App\Entity\Participant;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Repository\ParticipantRepository;
use App\Mapping\Participant\ParticipantDTO;
use App\Mapping\Participant\ParticipantOTD;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class ParticipantDTOAndOTDTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCanProcessParticipantObject()
    {
        $faker = Factory::create();

        $event = [
            'name' => $faker->word(),
            'description' => $faker->paragraph(),
            'startDate' => $faker->dateTime()->format('Y-m-d H:i:s'),
            'endDate' => $faker->dateTime()->format('Y-m-d H:i:s'),
            'tag' => $faker->word(),
            'capacity' => $faker->randomNumber(2),
            'owner_id' => 1,
        ];
        $eventRepository = new EventRepository();
        $eventRepository->insertOne($event);
        $eventObject = $eventRepository->findOneBy($event);

        $user = [
            'firstname' => $faker->firstName(),
            'name' => $faker->name(),
            'email' => $faker->email(),
            'password' => $faker->password(),
            'role' => $faker->randomElement(["admin", "BDE Members", "students"]),
            'isVerified' => 1,
            'signedUpDate' => $faker->dateTime()->format('Y-m-d H:i:s'),
        ];
        $userRepository = new UserRepository();
        $userRepository->insertOne($user);
        $userObject = $userRepository->findOneBy($user);


        // Creation of interested row (join event's id & user's id)
        $participant = [
            'event_id' => $eventObject->getId(),
            'user_id' => $userObject->getId()
        ];
        $participantRepository = new ParticipantRepository();
        $participantRepository->insertOne($participant);

        self::assertNotNull($participantRepository);

        $participantObject = $participantRepository->findOneBy($participant);
        self::assertInstanceOf(Participant::class, $participantObject);

        $participantRepository->delete($participant);
    }

    /**
     * @throws Exception
     */
    public function testCanProcessParticipantArray()
    {
        $faker = Factory::create();

        $event = new Event();

        $name = $faker->word();
        $description = $faker->paragraph();
        $startDate = $faker->dateTime();
        $endDate = $faker->dateTime();
        $tag = $faker->word();
        $capacity = $faker->randomNumber(2);
        $owner_id = 1;

        $event
            ->setId(7980)
            ->setName($name)
            ->setDescription($description)
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setTag($tag)
            ->setCapacity($capacity)
            ->setOwnerId($owner_id);


        $user = new User();
        $firstname = $faker->firstName();
        $name = $faker->name();
        $email = $faker->email();
        $password = $faker->password();
        $role = $faker->randomElement(["admin", "BDE Members", "students"]);
        $isVerified = 1;
        $signedUpDate = $faker->dateTime()->format('Y-m-d H:i:s');

        $user
            ->setId(8642)
            ->setFirstname($firstname)
            ->setName($name)
            ->setEmail($email)
            ->setPassword($password)
            ->setRole($role)
            ->setIsVerified($isVerified)
            ->setSignedUpDate($signedUpDate);

        $participantObject = new Participant();
        $participantObject
            ->setUserId($user->getId())
            ->setEventId($event->getId());

        $participantOTD = new ParticipantOTD();
        $participantArray = $participantOTD->config($participantObject)->process();

        self::assertSame($user->getId(), $participantArray['user_id']);
        self::assertSame($user->getId(), $participantArray['user_id']);

    }
}