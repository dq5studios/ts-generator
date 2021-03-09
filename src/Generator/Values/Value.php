<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Types\UndefinedType;
use Symfony\Polyfill\Php81\Php81;

/**
 * This is a value
 *
 * @template T
 */
abstract class Value
{
    /** @var class-string<T> */
    protected string $type = UndefinedType::class;

    private static function whichArrayObject(array $value): Value
    {
        /** @psalm-suppress InternalClass,InternalMethod */
        if (Php81::array_is_list($value)) {
            /** @var list<scalar|null> $value */
            return new ArrayValue($value);
        }
        /** @var array<string,scalar|null> $value */
        return new ObjectValue($value);
    }

    public static function from(mixed $value): Value
    {
        if ($value instanceof Value) {
            return $value;
        }
        $value = match (gettype($value)) {
            "boolean" => new BooleanValue($value),
            "integer" => new NumberValue($value),
            "double" => new NumberValue($value),
            "string" => new StringValue($value),
            "NULL" => new NullValue(),
            "array" => self::whichArrayObject($value),
            default => new UndefinedValue()
        };

        return $value;
    }

    /**
     * @return class-string<T>
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function __toString(): string
    {
        return (new Printer())->printValue($this);
    }
}
