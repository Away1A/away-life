<?php

declare(strict_types=1);

namespace App\Core;

use JsonSerializable;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use DateTime;

abstract class BaseEntity implements JsonSerializable
{
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function fill(array $attributes): static
    {
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {

            $name = $property->getName();

            if (!array_key_exists($name, $attributes)) {
                continue;
            }

            $value = $attributes[$name];

            $type = $property->getType();

            if (
                $type instanceof ReflectionNamedType &&
                $type->getName() === DateTime::class &&
                is_string($value)
            ) {
                $value = new DateTime($value);
            }

            $this->$name = $value;
        }

        return $this;
    }

    public function toArray(): array
    {
        $reflection = new ReflectionClass($this);

        $result = [];

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {

            $value = $property->getValue($this);

            if ($value instanceof DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }

            $result[$property->getName()] = $value;
        }

        return $result;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}