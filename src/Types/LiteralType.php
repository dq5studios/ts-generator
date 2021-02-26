<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use DQ5Studios\TypeScript\Generator\Values\Value;

/**
 * Value can be used as a type
 */
abstract class LiteralType extends PrimitiveType
{
    protected string $type = "";

    public static function from(Value $value): self
    {
        $type = new class extends LiteralType {
        };
        $type->type = (string) $value;
        return $type;
    }
}
