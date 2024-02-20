<?php

use App\Repository\EventRepository;
use App\Service\DB\DatabaseManager;
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

        $events = $eventRepository->findAll();

        foreach ($events as $event) {
            self::assertArrayHasKey('name', $event);
            self::assertArrayHasKey('description', $event);
            self::assertArrayHasKey('startDate', $event);
            self::assertArrayHasKey('endDate', $event);
            self::assertArrayHasKey('tag', $event);
            self::assertArrayHasKey('capacity', $event);
            self::assertArrayHasKey('owner_id', $event);
        }

        $eventRepository->delete(['name' => $name]);
    }

    /**
     * @throws Exception
     */
    public function testCanCreateAndRead()
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

        self::assertSame($name, $read['name']);
        self::assertSame($description, $read['description']);
        self::assertSame($startDate, $read['startDate']);
        self::assertSame($endDate, $read['endDate']);
        self::assertSame($tag, $read['tag']);
        self::assertSame($capacity, (int)$read['capacity']);
        self::assertSame($owner_id, (int)$read['owner_id']);

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

        assertSame($name, $read['name']);
        assertSame('new description', $read['description']);

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