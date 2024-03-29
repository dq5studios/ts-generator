<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Types\Interfaces\LiteralType;
use DQ5Studios\TypeScript\Generator\Types\NullType;
use DQ5Studios\TypeScript\Generator\Types\Traits\LiteralType as TraitLiteralType;

/**
 * A null value
 *
 * @template T as NullType
 *
 * @extends Value<T>
 */
class NullValue extends Value implements LiteralType
{
    use TraitLiteralType;

    /** @var class-string<T> */
    protected string $type = NullType::class;

    public function getValue(): bool|null
    {
        return null;
    }
}
