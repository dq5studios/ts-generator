<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use DQ5Studios\TypeScript\Generator\Printer;

/**
 * The array type, equivalent to PHP array
 */
class ArrayType extends MultiType
{
    protected string $type = "array";
    protected string $sep = "|";
}
