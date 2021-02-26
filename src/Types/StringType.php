<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * The string type, equivalent to PHP string
 */
class StringType extends PrimitiveType
{
    protected string $type = "string";
}
