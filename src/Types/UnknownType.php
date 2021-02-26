<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * The unknown type, no PHP equivalent
 */
class UnknownType extends PrimitiveType
{
    protected string $type = "unknown";
}
