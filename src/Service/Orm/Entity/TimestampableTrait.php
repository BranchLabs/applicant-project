<?php
/** @noinspection PhpUnused */

declare(strict_types=1);

namespace App\Service\Orm\Entity;

use DateTime;

trait TimestampableTrait
{
    /** @var DateTime */
    protected $createdAt;

    /** @var DateTime|null */
    protected $updatedAt;

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt = null): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function beforeCreate(): void
    {
        $this->setCreatedAt(new DateTime);
    }

    public function beforeUpdate(): void
    {
        $this->setUpdatedAt(new DateTime);
    }
}
