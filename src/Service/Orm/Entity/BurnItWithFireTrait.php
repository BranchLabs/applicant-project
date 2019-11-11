<?php

declare(strict_types=1);

namespace App\Service\Orm\Entity;

trait BurnItWithFireTrait
{
    /**
     * @param string $name
     * @param mixed $value
     */
    public function setData(string $name, $value): void
    {
        $method = 'set' . ucfirst($name);
        // Cleaner than using reflection
        if (method_exists($this, $method)) {
            $this->$method($value);
        }
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getData(string $name)
    {
        $method = 'get' . ucfirst($name);
        // Cleaner than using reflection
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return null;
    }
}
