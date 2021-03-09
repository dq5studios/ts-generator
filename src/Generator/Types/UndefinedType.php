<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * The undefined type, equivalent to PHP notset
 */
class UndefinedType extends PrimitiveType
{
    protected string $type = "undefined";
}
