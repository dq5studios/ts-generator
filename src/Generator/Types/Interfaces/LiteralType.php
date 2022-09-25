<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Interfaces;

use DQ5Studios\TypeScript\Generator\Types\Type;

/**
 * Value can be used as a type
 */
interface LiteralType
{
    public function asLiteral(): Type;
}
