<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Types\LiteralType;
use DQ5Studios\TypeScript\Generator\Types\LiteralTypeInterface;
use DQ5Studios\TypeScript\Generator\Types\NullType;
use DQ5Studios\TypeScript\Generator\Types\Type;

/**
 * A null value
 *
 * @template T as NullType
 * @extends Value<T>
 */
class NullValue extends Value implements LiteralTypeInterface
{
    /** @var class-string<T> */
    protected string $type = NullType::class;

    public function asLiteral(): Type
    {
        return LiteralType::from($this);
    }

    public function __toString(): string
    {
        return "null";
    }
}
