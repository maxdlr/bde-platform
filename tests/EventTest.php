<?php

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Service\DB\DatabaseManager;
use App\Service\DB\Entity;
use App\Service\DB\EntityManager;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertSame;

class EventTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCanFindAllEvent()
    {
        $eventRepository = new EventRepository();
        $faker = Factory::create();

        $name = $faker->word();

        for ($i = 0; $i < 50; $i++) {
            $event = new Event();
            $event
                ->setId($i + 999)
                ->setName($name)
                ->setDescription($faker->paragraph())
                ->setStartDate($faker->dateTime())
                ->setEndDate($faker->dateTime())
                ->setTag($faker->word())
                ->setCapacity($faker->randomNumber(2))
                ->setOwnerId(1);

            $eventRepository->insertOne($event);
        }

        $eventObjects = $eventRepository->findAll();

        self::assertNotNull($eventObjects);
        self::assertIsArray($eventObjects);
        self::assertInstanceOf(Entity::class, $eventObjects[rand(0, count($eventObjects) - 1)]);

        $eventRepository->delete(['name' => $name]);
    }

    /**
     * @throws Exception
     */
    public function testCanCreateEventAndFindOneBy()
    {
        $eventRepository = new EventRepository();
        $faker = Factory::create();

        $name = $faker->word();
        $description = $faker->paragraph();
        $startDate = $faker->dateTime();
        $endDate = $faker->dateTime();
        $tag = $faker->word();
        $capacity = $faker->randomNumber(2);
        $owner_id = 1;

        $event = new Event();
        $event
            ->setId($faker->randomNumber(1) + 999)
            ->setName($name)
            ->setDescription($description)
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setTag($tag)
            ->setCapacity($capacity)
            ->setOwnerId($owner_id);

        $eventRepository->insertOne($event);

        $read = $eventRepository->findOneBy(['name' => $name]);

        self::assertSame($name, $read->getName());
        self::assertSame($description, $read->getDescription());
        self::assertSame($startDate->format('Y-m-d H:i:s'), $read->getStartDate()->format('Y-m-d H:i:s'));
        self::assertSame($endDate->format('Y-m-d H:i:s'), $read->getEndDate()->format('Y-m-d H:i:s'));
        self::assertSame($tag, $read->getTag());
        self::assertSame($capacity, (int)$read->getCapacity());
        self::assertSame($owner_id, (int)$read->getOwnerId());

        $eventRepository->delete($event);
    }

    /**
     * @throws Exception
     */
    public function testCanCreateEventAndFindBy()
    {
        $eventRepository = new EventRepository();
        $faker = Factory::create();

        $name = $faker->word();
        $description = $faker->paragraph();
        $startDate = $faker->dateTime();
        $endDate = $faker->dateTime();
        $tag = $faker->word();
        $capacity = $faker->randomNumber(2);

        for ($i = 0; $i < 50; $i++) {
            $event = new Event();
            $event
                ->setId($i + 999)
                ->setName($name)
                ->setDescription($description)
                ->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setTag($tag)
                ->setCapacity($capacity)
                ->setOwnerId(1);

            $eventRepository->insertOne($event);
        }

        $events = $eventRepository->findBy(['name' => $name]);

        foreach ($events as $eventItem) {
            assertSame($event->getName(), $eventItem->getName());
            assertSame($event->getDescription(), $eventItem->getDescription());
            assertSame($event->getStartDate()->format('Y-m-d H:i:s'), $eventItem->getStartDate()->format('Y-m-d H:i:s'));
            assertSame($event->getEndDate()->format('Y-m-d H:i:s'), $eventItem->getEndDate()->format('Y-m-d H:i:s'));
            assertSame($event->getTag(), $eventItem->getTag());
            assertSame($event->getCapacity(), $eventItem->getCapacity());
            assertSame($event->getOwnerId(), $eventItem->getOwnerId());
        }

        self::assertCount(50, $events);

        $eventRepository->delete(['name' => $name]);
    }

    /**
     * @throws Exception
     */
    public function testCanUpdateEvent()
    {
        $eventRepository = new EventRepository();
        $faker = Factory::create();

        $name = $faker->word();
        $description = $faker->paragraph();
        $startDate = $faker->dateTime();
        $endDate = $faker->dateTime();
        $tag = $faker->word();
        $capacity = $faker->randomNumber(2);
        $owner_id = 1;

        $event = new Event();
        $event
            ->setId($faker->randomNumber(1) + 999)
            ->setName($name)
            ->setDescription($description)
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setTag($tag)
            ->setCapacity($capacity)
            ->setOwnerId($owner_id);

        $eventRepository->insertOne($event);

        $eventRepository->update(['description' => 'new description'], $event);

        $read = $eventRepository->findOneBy(['name' => $name]);

        assertSame($name, $read->getName());
        assertSame('new description', $read->getDescription());

        $eventRepository->delete(['name' => $name]);
    }

    /**
     * @throws Exception
     */
    public function testCanDeleteEvent()
    {
        $eventRepository = new EventRepository();
        $faker = Factory::create();

        $name = $faker->word();
        $description = $faker->paragraph();
        $startDate = $faker->dateTime();
        $endDate = $faker->dateTime();
        $tag = $faker->word();
        $capacity = $faker->randomNumber(2);
        $owner_id = 1;

        $event = new Event();
        $event
            ->setId($faker->randomNumber(1) + 999)
            ->setName($name)
            ->setDescription($description)
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setTag($tag)
            ->setCapacity($capacity)
            ->setOwnerId($owner_id);

        $eventRepository->insertOne($event);

        $eventRepository->delete($event);

        self::assertNull($eventRepository->findOneBy(['name' => $name]));
    }

    public function testCanEventToArray()
    {
        $faker = Factory::create();

        $name = $faker->word();
        $description = $faker->paragraph();
        $startDate = $faker->dateTime();
        $endDate = $faker->dateTime();
        $tag = $faker->word();
        $capacity = $faker->randomNumber(2);
        $owner_id = 1;

        $event = new Event();
        $event
            ->setId($faker->randomNumber(1) + 999)
            ->setName($name)
            ->setDescription($description)
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setTag($tag)
            ->setCapacity($capacity)
            ->setOwnerId($owner_id);

        $event = $event->toArray();

        self::assertIsArray($event);
    }
}