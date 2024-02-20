<?php

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
        $faker = Factory::create();

        $name = $faker->word();

        $event = [
            'name' => $name,
            'description' => $faker->paragraph(),
            'startDate' => $faker->dateTime()->format('Y-m-d H:i:s'),
            'endDate' => $faker->dateTime()->format('Y-m-d H:i:s'),
            'tag' => $faker->word(),
            'capacity' => $faker->randomNumber(2),
            'owner_id' => 1,
        ];

        for ($i = 0; $i < 50; $i++) {
            $eventRepository->insertOne($event);
        }

        $eventObjects = $eventRepository->findAll();

        self::assertNotNull($eventObjects);
        self::assertIsArray($eventObjects);
        self::assertInstanceOf(Entity::class, $eventObjects[rand(0, count($eventObjects))]);

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

        $read = $eventRepository->findOneBy(['name' => $name]);

        self::assertSame($name, $read->getName());
        self::assertSame($description, $read->getDescription());
        self::assertSame($startDate, $read->getStartDate()->format('Y-m-d H:i:s'));
        self::assertSame($endDate, $read->getEndDate()->format('Y-m-d H:i:s'));
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

        for ($i = 0; $i < 50; $i++) {
            $eventRepository->insertOne($event);
        }

        $events = $eventRepository->findBy($event);

        foreach ($events as $eventItem) {
            assertSame($event['name'], $eventItem->getName());
            assertSame($event['description'], $eventItem->getDescription());
            assertSame($event['startDate'], $eventItem->getStartDate()->format('Y-m-d H:i:s'));
            assertSame($event['endDate'], $eventItem->getEndDate()->format('Y-m-d H:i:s'));
            assertSame($event['tag'], $eventItem->getTag());
            assertSame($event['capacity'], $eventItem->getCapacity());
            assertSame($event['owner_id'], $eventItem->getOwnerId());
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

        $eventRepository->update(['description' => 'new description'], ['name' => $name]);

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

        $eventRepository->delete($event);

        self::assertNull($eventRepository->findOneBy($event));
    }
}