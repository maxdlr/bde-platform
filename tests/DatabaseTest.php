<?php

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Service\DB\DatabaseManager;
use App\Service\DB\Entity;
use App\Service\DB\EntityManager;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertSame;

class DatabaseTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCanConnectToDatabase()
    {
        $databaseManager = new DatabaseManager();
        $conn = $databaseManager->connect();

        self::assertSame('', $conn->error);
        self::assertInstanceOf(mysqli::class, $conn);
    }

    /**
     * @throws Exception
     */
    public function testCanExecuteMySqlRequests()
    {
        $entityManager = new EntityManager();

        $creationRequest = $entityManager->executeRequest('create table if not exists caca (id int not null auto_increment primary key);');
        self::assertSame(true, $creationRequest);

        $deletionRequest = $entityManager->executeRequest('drop table caca;');
        self::assertSame(true, $deletionRequest);
    }

    /**
     * @throws Exception
     */
    public function testCanFindAll()
    {
        $eventRepository = new EventRepository();
        $event = new Event();
        $faker = Factory::create();

        $name = $faker->word();

        $event
            ->setName($name)
            ->setDescription($faker->paragraph())
            ->setStartDate($faker->dateTime())
            ->setEndDate($faker->dateTime())
            ->setTag($faker->word())
            ->setCapacity($faker->randomNumber(2))
            ->setOwnerId(1);

        for ($i = 0; $i < 50; $i++) {
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
    public function testCanCreateAndFindOneBy()
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
    public function testCanCreateAndFindBy()
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
            ->setName($name)
            ->setDescription($description)
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setTag($tag)
            ->setCapacity($capacity)
            ->setOwnerId($owner_id);

        for ($i = 0; $i < 50; $i++) {
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

        $eventRepository->delete($event);
    }

    /**
     * @throws Exception
     */
    public function testCanUpdate()
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
    public function testCanDelete()
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
}