<?php

namespace App\DB;

use Exception;
use mysqli;
use Symfony\Component\Dotenv\Dotenv;

class DatabaseManager
{
    public function getEntityManager(): EntityManager
    {
        try {
            return new EntityManager();
        } catch (Exception $e) {
            var_dump('Missing Database driver', $e);
            exit();
        }
    }

    /**
     * @throws Exception
     */
    public function connect(): mysqli
    {
        $conn = new mysqli(
            $this->getDatabaseParameters()['host'],
            $this->getDatabaseParameters()['user'],
            $this->getDatabaseParameters()['password']
        );

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $use = 'use ' . $this->getDatabaseParameters()['dbname'] . ';';

        if (!$conn->query($use)) {
            throw new Exception('Unable to use this database');
        }

        return $conn;
    }

    public function getDatabaseParameters(): array
    {
        $dotenv = new Dotenv();
        try {
            $dotenv->loadEnv(__DIR__ . '/../../.env');
        } catch (Exception $e) {
            var_dump($e);
        }

        return [
            'driver' => $_ENV['DB_DRIVER'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
            'dbname' => $_ENV['DB_NAME'],
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
        ];
    }
}