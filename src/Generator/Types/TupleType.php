<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use DQ5Studios\TypeScript\Generator\Printer;

/**
 * The tuple type, similar but not equivalent to PHP array
 */
class TupleType extends MultiType
{
    protected string $type = "tuple";
    protected string $sep = ",";
}
