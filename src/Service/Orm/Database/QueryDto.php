<?php

declare(strict_types=1);

namespace App\Service\Orm\Database;

class QueryDto
{
    /** @var string */
    private $columns = '*';

    private $conditions = [];

    /** @var int|null */
    private $limit;

    /** @var int|null */
    private $offset;

    public function getColumns(): string
    {
        return $this->columns;
    }

    public function setColumns(string $columns): void
    {
        $this->columns = $columns;
    }

    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function setConditions(array $conditions): void
    {
        $this->conditions = $conditions;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit = null): void
    {
        $this->limit = $limit;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function setOffset(?int $offset = null): void
    {
        $this->offset = $offset;
    }
}
