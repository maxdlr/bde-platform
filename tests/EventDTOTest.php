<?php

use App\Entity\Event;
use App\Mapping\Event\EventDTO;
use App\Repository\EventRepository;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class EventDTOTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCanProcessEvent()
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

        $eventObject = $eventDto->config($event, new Event())->process();

        self::assertInstanceOf(Event::class, $eventObject);
    }
}