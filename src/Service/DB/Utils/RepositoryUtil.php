<?php

namespace App\Service\DB\Utils;

use Exception;

class RepositoryUtil
{
    /**
     * @throws Exception
     */
    static public function formatMysqlConditionClause(string $clause, array $columnAndValues): string|bool
    {

        return match ($clause) {
            'where' => ' ' . $clause . ' ' . self::formatValuesForWhere($columnAndValues),
            'set' => ' ' . $clause . ' ' . self::formatValuesForSet($columnAndValues),
            'insert' => self::formatValuesForInsert($columnAndValues),
            default => throw new Exception('Wrong clause. Choices are "where", "set" and "insert".')
        };
    }

    static private function formatValuesForInsert(array $columnAndValues): string
    {
        $columns = [];
        $values = [];

        foreach ($columnAndValues as $column => $value) {
            $columns[] = $column;
            $values[] = '"' . $value . '"';
        }

        return ' (' . implode(',', $columns) . ')' . ' values ' . '(' . implode(',', $values) . ');';
    }

    static private function formatValuesForSet(array $columnAndValues): string
    {
        $sql = '';
        $first = true;
        foreach ($columnAndValues as $column => $value) {
            if (!$first) {
                $sql .= ', ';
            }
            $sql .= $column . ' = "' . $value . '"';
            $first = false;
        }
        return $sql;
    }

    static private function formatValuesForWhere(array $columnAndValues): string
    {
        $sql = '';
        $first = true;
        foreach ($columnAndValues as $column => $value) {
            if (!$first) $sql .= ' and ';

            $sql .= $column . ' = "' . $value . '"';

            $first = false;
        }
        return $sql;
    }
}