<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * The boolean type, equivalent to PHP bool
 */
class BooleanType extends PrimitiveType
{
    protected string $type = "boolean";
}
