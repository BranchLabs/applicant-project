<?php

declare(strict_types=1);

namespace App\Service\Orm\Database\Adapter\Mysql;

use App\Service\Orm\Database\DatabaseInterface;
use App\Service\Orm\Database\QueryDto;
use PDO;

class MysqlAdapter implements DatabaseInterface
{
    /** @var MysqlCredentials */
    private $credentials;

    /** @var PDO */
    private $con;

    public function __construct(MysqlCredentials $credentials)
    {
        $this->credentials = $credentials;
        $this->connect();
    }

    public function connect(): void
    {
        $dsn = 'mysql:host=' . $this->credentials->getHost() .';dbname=' . $this->credentials->getDatabaseName();
        $this->con = new PDO($dsn, $this->credentials->getUsername(), $this->credentials->getPassword());
        $this->con->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }

    public function disconnect(): void
    {
        $this->con = null;
    }

    public function insert(string $table, array $data): bool
    {
        $sth = $this->con->prepare((new MysqlQueryBuilder)->getInsertSql($table, array_keys($data)));
        return $sth->execute($data);
    }

    public function update(string $table, array $data, ?array $conditions = []): bool
    {
        $sth = $this->con->prepare((new MysqlQueryBuilder)->getUpdateSql($table, array_keys($data), array_keys($conditions)));
        return $sth->execute(array_merge($data, $conditions));
    }

    public function query(string $table, QueryDto $dto): array
    {
        $sth = $this->con->prepare((new MysqlQueryBuilder)->getSelectSql($table, $dto));
        $sth->execute($dto->getConditions());

        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $sth->fetchAll();
    }

    public function count(string $table, ?array $conditions = []): int
    {
        $sth = $this->con->prepare((new MysqlQueryBuilder)->getCountSql($table, array_keys($conditions)));
        $sth->execute($conditions);
        return $sth->rowCount();
    }

    public function delete(string $table, array $conditions): bool
    {
        $sth = $this->con->prepare((new MysqlQueryBuilder)->getDeleteSql($table, array_keys($conditions)));
        return $sth->execute($conditions);
    }
}
