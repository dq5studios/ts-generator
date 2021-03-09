<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Types\Interfaces\LiteralType;
use DQ5Studios\TypeScript\Generator\Types\Traits\LiteralType as TraitLiteralType;
use DQ5Studios\TypeScript\Generator\Types\UndefinedType;

/**
 * An undefined value
 *
 * @template T as UndefinedType
 * @extends Value<T>
 */
class UndefinedValue extends Value implements LiteralType
{
    use TraitLiteralType;

    /** @var class-string<T> */
    protected string $type = UndefinedType::class;
}
