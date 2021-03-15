<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * The tuple type, similar but not equivalent to PHP array
 */
class TupleType extends MultiType
{
    protected string $type = "tuple";
    protected string $sep = ",";
}
