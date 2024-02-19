<?php

namespace App\DB;

use Exception;
use mysqli;
use mysqli_result;

class EntityManager
{
    private DatabaseManager $databaseManager;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->databaseManager = new DatabaseManager();
    }

    /**
     * @throws Exception
     */
    public function executeRequest(string $sql): array
    {
        $conn = $this->databaseManager->connect();
        assert($conn instanceof mysqli);

        if (!$conn->query($sql))
            throw new Exception('Unable to execute sql query' . $conn->error);

        assert($conn->query($sql) instanceof mysqli_result);

        $results = $conn->query($sql)->fetch_all();

        $extractedResults = [];
        foreach ($results as $result) {
            $extractedResults[] = $result[0];
        }

        return $extractedResults;
    }
}