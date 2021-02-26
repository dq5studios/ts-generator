<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Types\LiteralType;
use DQ5Studios\TypeScript\Generator\Types\LiteralTypeInterface;
use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Types\UndefinedType;

/**
 * An undefined value
 *
 * @template T as UndefinedType
 * @extends Value<T>
 */
class UndefinedValue extends Value implements LiteralTypeInterface
{
    /** @var class-string<T> */
    protected string $type = UndefinedType::class;

    public function asLiteral(): Type
    {
        return LiteralType::from($this);
    }

    public function __toString(): string
    {
        return "undefined";
    }
}
