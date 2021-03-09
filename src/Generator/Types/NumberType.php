<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * The number type, equivalent to PHP int | float
 */
class NumberType extends PrimitiveType
{
    protected string $type = "number";
}
