<?php

namespace App\DB;

use Exception;
use stdClass;

abstract class Repository extends EntityManager
{
    protected string $tableName;

    public function __construct()
    {
        parent::__construct();
        $this->tableName = '';
    }

//    ------------- CREATE -------------------------------------------------------

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
            $values[] = '"' . $value . '"';
        }

        $sql .= ' (' . implode(',', $columns) . ')' . ' values ' . '(' . implode(',', $values) . ');';
        return $this->executeRequest($sql);
    }

//    ------------- READ -------------------------------------------------------

    /**
     * @throws Exception
     */
    public function findAll(): null|array
    {
        if ($this->tableName === '')
            throw new Exception('Table name unknown or undefined.');

        $sql = "select * from $this->tableName;";
        return $this->executeRequest($sql);
    }

    /**
     * @throws Exception
     */
    public function findOneBy(array $criteria): null|array
    {
        if ($this->tableName === '')
            throw new Exception('Table name unknown or undefined.');

        $sql = 'select * from ' . $this->tableName . ' where ';
        $first = true;
        foreach ($criteria as $column => $value) {
            if (!$first) {
                $sql .= ' and ' . $column . ' = "' . $value . '"';
            } else {
                $sql .= $column . ' = "' . $value . '"';
            }
            $first = false;
        }

        $sql .= ' limit 1;';

        $result = $this->executeRequest($sql);

        return $result ? $result[0] : $result;
    }

//    ------------- UPDATE -------------------------------------------------------

    /**
     * @throws Exception
     */
    public function update(array $columnAndValues, array $criteria): bool
    {
        if ($this->tableName === '')
            throw new Exception('Table name unknown or undefined.');

        $sql = 'update ' . $this->tableName . ' set ';

        $first = true;
        foreach ($columnAndValues as $column => $value) {
            if (!$first) {
                $sql .= ', ';
            }
            $sql .= $column . ' = "' . $value . '"';
            $first = false;
        }
        $sql .= ' where ';

        $first = true;
        foreach ($criteria as $column => $value) {
            if (!$first) {
                $sql .= ' and ' . $column . ' = "' . $value . '"';
            } else {
                $sql .= $column . ' = "' . $value . '"';
            }
            $first = false;
        }
        $sql .= ';';

        return $this->executeRequest($sql);
    }

//    ------------- DELETE -------------------------------------------------------

    /**
     * @throws Exception
     */
    public function delete(array $criteria): bool
    {
        if ($this->tableName === '')
            throw new Exception('Table name unknown or undefined.');

        $sql = 'delete from ' . $this->tableName . ' where ';

        $first = true;
        foreach ($criteria as $column => $value) {
            if (!$first) {
                $sql .= ' and ' . $column . ' = "' . $value . '"';
            } else {
                $sql .= $column . ' = "' . $value . '"';
            }
            $first = false;
        }
        $sql .= ';';

        return $this->executeRequest($sql);
    }
}