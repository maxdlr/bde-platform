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
    public function executeRequest(string $sql): array|bool
    {
        $conn = $this->databaseManager->connect();
        assert($conn instanceof mysqli);

        $query = $conn->query($sql);

        if (!$query)
            throw new Exception('Unable to execute sql query' . $conn->error);

        assert($query instanceof mysqli_result || is_bool($query));

        if (is_bool($query)) {
            var_dump('Request completed successfully');
            return true;
        }

        $results = $query->fetch_all();

        $extractedResults = [];
        foreach ($results as $result) {
            $extractedResults[] = $result[0];
        }

        return $extractedResults;
    }
}