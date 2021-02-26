<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Types\BooleanType;
use DQ5Studios\TypeScript\Generator\Types\LiteralType;
use DQ5Studios\TypeScript\Generator\Types\LiteralTypeInterface;
use DQ5Studios\TypeScript\Generator\Types\Type;

/**
 * A boolean value
 *
 * @template T as BooleanType
 * @extends Value<T>
 */
class BooleanValue extends Value implements LiteralTypeInterface
{
    /** @var class-string<T> */
    protected string $type = BooleanType::class;

    public function __construct(protected bool $value)
    {
    }

    public function getValue(): bool
    {
        return $this->value;
    }

    public function setValue(bool $value): self
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
        return $this->value ? "true" : "false";
    }
}
