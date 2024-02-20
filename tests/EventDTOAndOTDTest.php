<?php

use App\Entity\Event;
use App\Mapping\Event\EventDTO;
use App\Mapping\Event\EventOTD;
use App\Repository\EventRepository;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class EventDTOAndOTDTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCanProcessEventObject()
    {
        $eventRepository = new EventRepository();
        $eventDto = new EventDTO();
        $faker = Factory::create();

        $name = $faker->word();
        $description = $faker->paragraph();
        $startDate = $faker->dateTime()->format('Y-m-d H:i:s');
        $endDate = $faker->dateTime()->format('Y-m-d H:i:s');
        $tag = $faker->word();
        $capacity = $faker->randomNumber(2);
        $owner_id = 1;

        $event = [
            'name' => $name,
            'description' => $description,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'tag' => $tag,
            'capacity' => $capacity,
            'owner_id' => $owner_id,
        ];
        $eventRepository->insertOne($event);

        $eventObject = $eventDto->config($event)->process();

        self::assertInstanceOf(Event::class, $eventObject);

        self::assertSame($eventObject->getName(), $event['name']);
        self::assertSame($eventObject->getDescription(), $event['description']);
        self::assertSame($eventObject->getStartDate()->format('Y-m-d H:i:s'), $event['startDate']);
        self::assertSame($eventObject->getEndDate()->format('Y-m-d H:i:s'), $event['endDate']);
        self::assertSame($eventObject->getTag(), $event['tag']);
        self::assertSame($eventObject->getCapacity(), $event['capacity']);
        self::assertSame($eventObject->getOwnerId(), $event['owner_id']);

        $eventRepository->delete($event);
    }

    /**
     * @throws Exception
     */
    public function testCanProcessEventArray()
    {
        $event = new Event();
        $faker = Factory::create();

        $name = $faker->word();
        $description = $faker->paragraph();
        $startDate = $faker->dateTime();
        $endDate = $faker->dateTime();
        $tag = $faker->word();
        $capacity = $faker->randomNumber(2);
        $owner_id = 1;

        $event
            ->setName($name)
            ->setDescription($description)
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setTag($tag)
            ->setCapacity($capacity)
            ->setOwnerId($owner_id);

        $eventOTD = new EventOTD();

        $eventArray = $eventOTD->config($event)->process();

        self::assertIsArray($eventArray);

        self::assertSame($event->getName(), $eventArray['name']);
        self::assertSame($event->getDescription(), $eventArray['description']);
        self::assertSame($event->getStartDate()->format('Y-m-d H:i:s'), $eventArray['startDate']);
        self::assertSame($event->getEndDate()->format('Y-m-d H:i:s'), $eventArray['endDate']);
        self::assertSame($event->getTag(), $eventArray['tag']);
        self::assertSame($event->getCapacity(), $eventArray['capacity']);
        self::assertSame($event->getOwnerId(), $eventArray['owner_id']);
    }
}