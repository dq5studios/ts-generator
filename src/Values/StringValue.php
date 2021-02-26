<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Types\LiteralType;
use DQ5Studios\TypeScript\Generator\Types\LiteralTypeInterface;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use DQ5Studios\TypeScript\Generator\Types\Type;

/**
 * A string value
 *
 * @template T as StringType
 * @extends Value<T>
 */
class StringValue extends Value implements LiteralTypeInterface
{
    /** @var class-string<T> */
    protected string $type = StringType::class;

    public function __construct(protected string $value)
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
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
        return "\"" . $this->value . "\"";
    }
}
