<?php
/** @noinspection PhpUnused */

declare(strict_types=1);

namespace App\Entity;

use App\Service\Orm\Entity\AbstractEntity;
use App\Service\Orm\Entity\TimestampableTrait;

class Contact extends AbstractEntity
{
    use TimestampableTrait;

    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $surname;

    /** @var string */
    private $email;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname = null): void
    {
        $this->surname = $surname;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getFullName(): string
    {
        return trim($this->getName() . ' ' . $this->getSurname());
    }
}
