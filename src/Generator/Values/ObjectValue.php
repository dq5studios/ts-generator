<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Types\ObjectType;

/**
 * A string value
 *
 * @template T as ObjectType
 *
 * @extends Value<T>
 */
class ObjectValue extends Value
{
    /** @var class-string<T> */
    protected string $type = ObjectType::class;
    /** @var array<string,Value> */
    protected array $value = [];

    /** @param array<string,scalar|null> $values */
    public function __construct(array $values = [])
    {
        foreach ($values as $key => $value) {
            $this->value[$key] = Value::from($value);
        }
    }

    public function getValue(): array
    {
        return $this->value;
    }
}
