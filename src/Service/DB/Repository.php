<?php

namespace App\Service\DB;

use App\Mapping\DTO;
use App\Service\DB\Utils\RepositoryUtil;
use Exception;

abstract class Repository extends EntityManager
{
    protected ?string $tableName;
    protected ?DTO $dto;

    public function __construct()
    {
        parent::__construct();
        $this->tableName = null;
        $this->dto = null;
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
        assert($this->isTableSet() && $this->isDtoSet());

        $sql = "select * from $this->tableName;";
        $arrays = $this->executeRequest($sql);

        return $this->toObject($arrays);
    }

    /**
     * @throws Exception
     */
    public function findBy(array $criteria): null|array
    {
        assert($this->isTableSet());

        $sql = 'select * from ' . $this->tableName;
        $sql .= RepositoryUtil::formatMysqlConditionClause('where', $criteria);
        $sql .= ';';
        $arrays = $this->executeRequest($sql);

        return $this->toObject($arrays);
    }

    /**
     * @throws Exception
     */
    public function findOneBy(array $criteria): null|object
    {

        assert($this->isTableSet());

        $sql = 'select * from ' . $this->tableName;
        $sql .= RepositoryUtil::formatMysqlConditionClause('where', $criteria);
        $sql .= ' limit 1;';

        $arrays = $this->executeRequest($sql);

        return $arrays ? $this->toObject($arrays)[0] : null;
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

    /**
     * @throws Exception
     */
    private function isDtoSet(): bool
    {
        if (is_null($this->dto)) {
            throw new Exception('Table of repository is not set.');
        }

        return true;
    }

    private function toObject(array $arrays): array
    {
        $objects = [];
        foreach ($arrays as $array) {
            $objects[] = $this->dto->config($array)->process();
        }

        return $objects;
    }
}