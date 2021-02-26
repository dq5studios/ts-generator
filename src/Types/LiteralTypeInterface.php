<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * Value can be used as a type
 */
interface LiteralTypeInterface
{
    public function asLiteral(): Type;
}
