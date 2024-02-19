<?php

use App\DB\DatabaseManager;
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

}