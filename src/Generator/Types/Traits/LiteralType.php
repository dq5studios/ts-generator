<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Types\PrimitiveType;
use DQ5Studios\TypeScript\Generator\Types\Type;

/**
 * Value can be used as a type
 */
trait LiteralType
{
    public function asLiteral(): Type
    {
        $type = new class () extends PrimitiveType {
            protected string $type = "";
        };
        $type->setType(Printer::print($this));

        return $type;
    }
}
