<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Types\Interfaces\LiteralType;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use DQ5Studios\TypeScript\Generator\Types\Traits\LiteralType as TraitLiteralType;

/**
 * A string value
 *
 * @template T as StringType
 *
 * @extends Value<T>
 */
class StringValue extends Value implements LiteralType
{
    use TraitLiteralType;

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
}
