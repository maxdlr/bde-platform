<?php

use App\Entity\Event;
use App\Entity\User;
use App\Entity\Interested;
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
            'signedUpOn' => $faker->dateTime()->format('Y-m-d H:i:s'),
        ];
        $userRepository = new UserRepository();
        $userRepository->insertOne($user);
        $userObject = $userRepository->findOneBy($user);


        // Creation of interested row (join event's id & user's id)
        $interested = [
            'event_id' => $eventObject->getId(),
            'user_id' => $userObject->getId()
        ];
        $interestedRepository = new InterestedRepository();
        $interestedRepository->insertOne($interested);

        self::assertNotNull($interestedRepository);

        $interestedObject = $interestedRepository->findOneBy($interested);
        self::assertInstanceOf(Interested::class, $interestedObject);

        $interestedRepository->delete($interested);
    }

    /**
     * @throws Exception
     */
    public function testCanProcessInterestedArray()
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
            ->setId(7610)
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
        $signedUpOn = $faker->dateTime()->format('Y-m-d H:i:s');

        $user
            ->setId(8951)
            ->setFirstname($firstname)
            ->setName($name)
            ->setEmail($email)
            ->setPassword($password)
            ->setRole($role)
            ->setIsVerified($isVerified)
            ->setSignedUpOn($signedUpOn);

        $interestedObject = new Interested();
        $interestedObject
            ->setUserId($user->getId())
            ->setEventId($event->getId());

        $interestedOTD = new InterestedOTD();
        $interestedArray = $interestedOTD->config($interestedObject)->process();

        self::assertSame($user->getId(), $interestedArray['user_id']);
        self::assertSame($user->getId(), $interestedArray['user_id']);

    }
}