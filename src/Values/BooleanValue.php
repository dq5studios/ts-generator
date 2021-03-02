<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Types\BooleanType;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\LiteralType;
use DQ5Studios\TypeScript\Generator\Types\Traits\LiteralType as TraitLiteralType;

/**
 * A boolean value
 *
 * @template T as BooleanType
 * @extends Value<T>
 */
class BooleanValue extends Value implements LiteralType
{
    use TraitLiteralType;

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

    public function __toString(): string
    {
        return $this->value ? "true" : "false";
    }
}
