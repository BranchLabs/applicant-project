<?php

declare(strict_types=1);

namespace App\Service\Orm\Database\Adapter\Mysql;

use App\Service\Orm\Database\QueryDto;

class MysqlQueryBuilder
{
    public function getSelectSql(string $tableName, QueryDto $dto): string
    {
        $sql = 'SELECT ' . $dto->getColumns() . ' FROM `' . $tableName .'`';

        if ($dto->getConditions()) {
            $sql .= ' WHERE ' . $this->getNamedStringByKeys(array_keys($dto->getConditions()));
        }

        if ($dto->getLimit()) {
            $sql .= ' LIMIT ' . $dto->getLimit();
        }

        if ($dto->getOffset()) {
            $sql .= ' OFFSET ' . $dto->getOffset();
        }

        return $sql;
    }

    public function getInsertSql(string $tableName, array $keys): string
    {
        $sql = 'INSERT INTO `' . $tableName . '` (' . implode(', ', $keys) . ') VALUE ';
        $sql .= '(:' . implode(', :', $keys) . ')';

        return $sql;
    }

    public function getUpdateSql(string $tableName, array $keys, ?array $conditionKeys = []): string
    {
        $sql = 'UPDATE `' . $tableName . '` SET ' . $this->getNamedStringByKeys($keys, ', ');

        if (!$conditionKeys) {
            return $sql;
        }

        $sql .= ' WHERE ' . $this->getNamedStringByKeys($conditionKeys);
        return $sql;
    }

    public function getDeleteSql(string $tableName, array $conditionKeys): string
    {
        return 'DELETE FROM `' . $tableName . '` WHERE ' . $this->getNamedStringByKeys($conditionKeys);
    }

    public function getCountSql(string $tableName, ?array $conditionKeys = []): string
    {
        $sql = 'SELECT COUNT(1) FROM `' . $tableName . '`';

        if ($conditionKeys) {
            $sql .= ' WHERE ' . $this->getNamedStringByKeys($conditionKeys);
        }

        return $sql;
    }

    private function getNamedStringByKeys(array $keys, string $delimiter = ' '): string
    {
        $str = '';
        foreach ($keys as $key) {
            $str .= sprintf('%s = :%1$s', $key) . $delimiter;
        }

        return trim($str, $delimiter);
    }
}
