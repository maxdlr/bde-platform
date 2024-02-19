<?php

namespace App\DB;

use Exception;

abstract class Repository extends EntityManager
{
    protected string $tableName;

    public function __construct()
    {
        parent::__construct();
        $this->tableName = '';
    }

    /**
     * @throws Exception
     */
    public function findAll(): array
    {
        if ($this->tableName === '')
            throw new Exception('Table name unknown or undefined.');

        $sql = "select * from $this->tableName;";
        return $this->executeRequest($sql);
    }

    /**
     * @throws Exception
     */
    public function findOneBy(array $criteria): array
    {
        if ($this->tableName === '')
            throw new Exception('Table name unknown or undefined.');

        $sql = 'select * from ' . $this->tableName . ' where ';
        $first = true;
        foreach ($criteria as $field => $value) {
            if (!$first) {
                $sql .= 'and ' . $field . ' = "' . $value . '"';
            } else {
                $sql .= $field . ' = "' . $value . '"';
            }
            $first = false;
        }

        $sql .= ';';
        return $this->executeRequest($sql);
    }

    /**
     * @throws Exception
     */
    public function insertOne(array $columnAndValues): bool
    {
        if ($this->tableName === '')
            throw new Exception('Table name unknown or undefined.');

        $sql = 'insert into ' . $this->tableName;

        $columns = [];
        $values = [];

        foreach ($columnAndValues as $column => $value) {
            $columns[] = $column;
            $values[] = $value;
        }

        $sql .= ' (' . implode(',', $columns) . ')' . ' values ' . implode(',', $values) . ');';
        return $this->executeRequest($sql);
    }
}