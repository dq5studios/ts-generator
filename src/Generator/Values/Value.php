<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Types\UndefinedType;

/**
 * This is a value
 *
 * @template T
 */
abstract class Value
{
    /** @var class-string<T> */
    protected string $type = UndefinedType::class;

    private static function whichArrayObject(array $value): self
    {
        if (array_is_list($value)) {
            /** @var list<scalar|null> $value */
            return new ArrayValue($value);
        }

        /** @var array<string,scalar|null> $value */
        return new ObjectValue($value);
    }

    public static function from(mixed $value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        return match (get_debug_type($value)) {
            "bool" => new BooleanValue($value),
            "int" => new NumberValue($value),
            "float" => new NumberValue($value),
            "string" => new StringValue($value),
            "null" => new NullValue(),
            "array" => self::whichArrayObject($value),
            default => new UndefinedValue(),
        };
    }

    /**
     * @return class-string<T>
     */
    public function getType(): string
    {
        return $this->type;
    }
}
