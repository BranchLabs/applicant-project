<?php

declare(strict_types=1);

namespace App\Service\Orm\Entity;

interface EntityInterface
{
    public function hydrate(array $data);
}
