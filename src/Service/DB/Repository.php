<?php

namespace App\Service\DB;

use App\Service\DB\Utils\RepositoryUtil;
use Exception;

abstract class Repository extends EntityManager
{
    protected ?string $tableName;

    public function __construct()
    {
        parent::__construct();
        $this->tableName = null;
    }

//    ------------- CREATE -------------------------------------------------------

    /**
     * @throws Exception
     */
    public function insertOne(array $columnAndValues): bool
    {
        assert($this->isTableSet());

        $sql = 'insert into ' . $this->tableName;
        $sql .= RepositoryUtil::formatMysqlConditionClause('insert', $columnAndValues);

        return $this->executeRequest($sql);
    }

//    ------------- READ -------------------------------------------------------

    /**
     * @throws Exception
     */
    public function findAll(): null|array
    {
        assert($this->isTableSet());

        $sql = "select * from $this->tableName;";
        return $this->executeRequest($sql);
    }

    /**
     * @throws Exception
     */
    public function findOneBy(array $criteria): null|array
    {
        assert($this->isTableSet());

        $sql = 'select * from ' . $this->tableName;
        $sql .= RepositoryUtil::formatMysqlConditionClause('where', $criteria);
        $sql .= ' limit 1;';

        $result = $this->executeRequest($sql);

        return $result ? $result[0] : null;
    }

//    ------------- UPDATE -------------------------------------------------------

    /**
     * @throws Exception
     */
    public function update(array $columnAndValues, array $criteria): bool
    {
        assert($this->isTableSet());

        $sql = 'update ' . $this->tableName;
        $sql .= RepositoryUtil::formatMysqlConditionClause('set', $columnAndValues);
        $sql .= RepositoryUtil::formatMysqlConditionClause('where', $criteria);
        $sql .= ';';

        return $this->executeRequest($sql);
    }

//    ------------- DELETE -------------------------------------------------------

    /**
     * @throws Exception
     */
    public function delete(array $criteria): bool
    {
        assert($this->isTableSet());

        $sql = 'delete from ' . $this->tableName;
        $sql .= RepositoryUtil::formatMysqlConditionClause('where', $criteria);
        $sql .= ';';

        return $this->executeRequest($sql);
    }

    /**
     * @throws Exception
     */
    private function isTableSet(): bool
    {
        if (is_null($this->tableName)) {
            throw new Exception('Table of repository is not set.');
        }

        return true;
    }
}