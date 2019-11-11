<?php

declare(strict_types=1);

namespace App\Service\Orm\Database;

interface DatabaseInterface
{
    public function connect();

    public function disconnect();

    public function insert(string $table, array $data): bool;

    public function update(string $table, array $data, ?array $conditions = []): bool;

    public function query(string $table, QueryDto $dto): array;

    public function count(string $table, ?array $conditions = []): int;

    public function delete(string $table, array $conditions): bool;
}
