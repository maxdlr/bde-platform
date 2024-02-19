<?php

use App\DB\DatabaseManager;
use App\DB\EntityManager;
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

    public function testCanExecuteMySqlRequest()
    {
        $entityManager = new EntityManager();
        $request = $entityManager->executeRequest('create table if not exists caca (id int not null auto_increment primary key);');

        var_dump($request);
        die();
    }
}