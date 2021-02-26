<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Types\LiteralType;
use DQ5Studios\TypeScript\Generator\Types\LiteralTypeInterface;
use DQ5Studios\TypeScript\Generator\Types\NumberType;
use DQ5Studios\TypeScript\Generator\Types\Type;

/**
 * A number value
 *
 * @template T as NumberType
 * @extends Value<T>
 */
class NumberValue extends Value implements LiteralTypeInterface
{
    /** @var class-string<T> */
    protected string $type = NumberType::class;

    public function __construct(protected int | float $value)
    {
    }

    public function getValue(): int | float
    {
        return $this->value;
    }

    public function setValue(int | float $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function asLiteral(): Type
    {
        return LiteralType::from($this);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
