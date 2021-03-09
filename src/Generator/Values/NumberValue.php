<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Types\Interfaces\LiteralType;
use DQ5Studios\TypeScript\Generator\Types\Traits\LiteralType as TraitLiteralType;
use DQ5Studios\TypeScript\Generator\Types\NumberType;

/**
 * A number value
 *
 * @template T as NumberType
 * @extends Value<T>
 */
class NumberValue extends Value implements LiteralType
{
    use TraitLiteralType;

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
}
