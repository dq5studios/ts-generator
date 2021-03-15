<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Types\ArrayType;

/**
 * A string value
 *
 * @template T as ArrayType
 * @extends Value<T>
 */
class ArrayValue extends Value
{
    /** @var class-string<T> */
    protected string $type = ArrayType::class;
    /** @var Value[] $value */
    protected array $value = [];

    /** @param list<scalar|null> $values */
    public function __construct(array $values = [])
    {
        foreach ($values as $value) {
            $this->value[] = Value::from($value);
        }
    }

    public function getValue(): array
    {
        return $this->value;
    }
}
