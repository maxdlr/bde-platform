<?php

namespace App\Service\DB;

use App\Mapping\DTO;
use App\Mapping\OTD;
use App\Service\DB\Utils\RepositoryUtil;
use Exception;

abstract class Repository extends EntityManager
{
    protected ?string $tableName;
    protected ?DTO $dto;
    protected ?OTD $otd;

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
    public function insertOne(object $object): bool
    {
        assert($this->isTableSet() && $this->isDataTransferSet());

        $columnAndValues = $this->toDbModel($object);

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
        assert($this->isTableSet() && $this->isDataTransferSet());

        $sql = "select * from $this->tableName;";
        $arrays = $this->executeRequest($sql);

        return $this->toObject($arrays);
    }

    /**
     * @throws Exception
     */
    public function findBy(array $criteria): null|array
    {
        assert($this->isTableSet() && $this->isDataTransferSet());

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
        assert($this->isTableSet() && $this->isDataTransferSet());

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
    public function update(array $columnAndValues, array|object $mixed): bool
    {
        assert($this->isTableSet() && $this->isDataTransferSet());

        $criteria = $mixed instanceof Entity ? $this->toDbModel($mixed) : $mixed;

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
    public function delete(array|object $mixed): bool
    {
        assert($this->isTableSet() && $this->isDataTransferSet());

        $criteria = $mixed instanceof Entity ? $this->toDbModel($mixed) : $mixed;

        $sql = 'delete from ' . $this->tableName;
        $sql .= RepositoryUtil::formatMysqlConditionClause('where', $criteria);
        $sql .= ';';

        return $this->executeRequest($sql);
    }

//    CHECKS ---------------------------------------------------------------------------------------------------

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
    private function isDataTransferSet(): bool
    {
        if (is_null($this->dto) || is_null($this->otd)) {
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

    private function toDbModel(array|object $objects): array
    {
        if ($objects instanceof Entity) {
            return $this->otd->config($objects)->process();
        }

        $arrays = [];
        foreach ($objects as $object) {
            assert($object instanceof Entity);
            $arrays[] = $this->otd->config($object)->process();
        }

        return $arrays;
    }
}