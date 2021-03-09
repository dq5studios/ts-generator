<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * The null type, equivalent to PHP null
 */
class NullType extends PrimitiveType
{
    protected string $type = "null";
}
