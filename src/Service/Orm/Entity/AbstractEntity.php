<?php

declare(strict_types=1);

namespace App\Service\Orm\Entity;

use Exception;
use ReflectionClass;
use ReflectionParameter;
use ReflectionException;
use DateTime;

// TODO RPoC Clean it up prototype / demonstration purpose and move to a serializer on ORM Adapter level.
// Keeping it simple!
// Normally we would implement a serializer in ORM to normalize / denormalize values and prepare for hydration
// This would also eliminate need for hydrate and toArray methods as they are easier / shorter this way
abstract class AbstractEntity implements EntityInterface
{
    public function hydrate(array $data): self
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst(str_replace('_', '', ucwords($key, '_')));
            if (!method_exists($this, $method)) {
                continue;
            }

            try {
                /** @noinspection PhpParamsInspection */
                $refParam = new ReflectionParameter([static::class, $method], 0);
                $this->$method($this->getParamValue((string)$refParam->getType(), $value));
            }
            catch (ReflectionException $e) {
                $this->$method($value);
            }
            catch (Exception $e) {
                // This is datetime, skipping for now
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $reflection = new ReflectionClass($this);

        $data = [];
        foreach ($reflection->getProperties() as $prop) {
            $prop->setAccessible(true);
            $name = preg_replace('#[A-Z]([A-Z](?![a-z]))*#', '_$0', $prop->getName());
            $name = ltrim(strtolower($name), '_');
            $data[$name] = $this->getSerializedValue($prop->getValue($this));
        }

        return $data;
    }

    /**
     * @param string $type
     * @param null|mixed $value
     *
     * @return null|mixed
     * @throws Exception
     */
    protected function getParamValue(string $type, $value = null)
    {
        switch($type) {
            case 'bool':
                return (bool)$value;
            case 'int':
                return (int)$value;
            case 'array':
                return $value ? json_decode($value, true) : $value;
            case 'DateTime':
                /** @noinspection PhpUnhandledExceptionInspection */
                return $value ? new DateTime($value) : null;
            case 'string':
            default:
                return $value;
        }
    }

    /**
     * @param null|mixed $value
     *
     * @return null|mixed
     */
    protected function getSerializedValue($value = null)
    {
        if ($value instanceof DateTime) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        return $value;
    }
}
