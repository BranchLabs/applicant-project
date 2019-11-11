<?php

declare(strict_types=1);

namespace App\Service\Orm\Repository;

use App\Service\Orm\Database\DatabaseInterface;
use App\Service\Orm\Database\QueryDto;
use App\Service\Orm\Entity\EntityInterface;
use Ramsey\Uuid\Uuid;
use SplObjectStorage;

abstract class AbstractRepository
{
    /** @var DatabaseInterface */
    private $database;

    /** @var string */
    private $entity;

    abstract protected function getPrimaryKey(): string;

    abstract protected function getTableName(): string;

    public function __construct(DatabaseInterface $database, string $entity)
    {
        $this->database = $database;
        $this->entity = $entity;
    }

    public function find(string $id): ?EntityInterface
    {
        $resp = $this->findBy([
            'id' => $id,
        ]);

        if (!$resp) {
            return null;
        }

        /** @var EntityInterface $obj */
        $obj = $resp->current();
        return $obj;
    }

    public function findAll(): ?SplObjectStorage
    {
        return $this->findBy();
    }

    public function findBy(?array $conditions = []): ?SplObjectStorage
    {
        $dto = new QueryDto;
        $dto->setConditions($conditions);

        $records = $this->database->query($this->getTableName(), $dto);

        if (!$records) {
            return null;
        }

        $collection = new SplObjectStorage;
        foreach ($records as $record) {
            /** @var EntityInterface $obj */
            $obj = new $this->entity;
            $obj->hydrate($record);

            $collection->attach($obj);
        }

        return $collection;
    }

    public function count(?array $conditions = []): int
    {
        return $this->database->count($this->getTableName(), $conditions);
    }

    public function save(EntityInterface $entity): bool
    {
        return $entity->getId()? $this->update($entity) : $this->create($entity);
    }

    public function create(EntityInterface $entity): bool
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $id = Uuid::uuid4();
        $entity->setId($id->toString());

        // Can be event / observer etc., keeping it simple
        if (method_exists($entity, 'beforeCreate')) {
            $entity->beforeCreate();
        }

        return $this->database->insert($this->getTableName(), $entity->toArray());
    }

    public function update(EntityInterface $entity): bool
    {
        // Can be event / observer etc., keeping it simple
        if (method_exists($entity, 'beforeUpdate')) {
            $entity->beforeUpdate();
        }

        $data = $entity->toArray();
        $conditions = [];

        if (isset($data[$this->getPrimaryKey()])) {
            $conditions[$this->getPrimaryKey()] = $data[$this->getPrimaryKey()];
            unset($data[$this->getPrimaryKey()]);
        }

        $this->database->update($this->getTableName(), $data, $conditions);

        return false;
    }

    public function delete(string $id): bool
    {
        return $this->database->delete($this->getTableName(), ['id' => $id]);
    }
}
