<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * The bigint type, equivalent to PHP int
 */
class BigIntType extends PrimitiveType
{
    protected string $type = "bigint";
}
