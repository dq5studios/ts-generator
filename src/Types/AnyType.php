<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * The any type, equivalent to PHP mixed
 */
class AnyType extends PrimitiveType
{
    protected string $type = "any";
}
