<?php

use App\DB\DatabaseManager;
use App\DB\EntityManager;
use App\Repository\EventRepository;
use PHPUnit\Framework\TestCase;

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
        $events = $eventRepository->findAll();

        //todo
        //asserts
    }

    /**
     * @throws Exception
     */
    public function testCanFindOneBy()
    {
        $eventRepository = new EventRepository();
        $event = $eventRepository->findOneBy(['name' => 'soiree']);

        //todo
        //asserts
    }

    /**
     * @throws Exception
     */
    public function testCanCreateObject()
    {
        $eventRepository = new EventRepository();
        $event = $eventRepository->insertOne([
            'name' => 'name1',
            'description' => 'description1',
            'startDate' => '2024-02-19 20:10:59',
            'endDate' => '2024-02-19 20:10:59',
            'tag' => 'tag1',
            'capacity' => 1,
            'owner_id' => 1
        ]);

        var_dump($event);

    }
}