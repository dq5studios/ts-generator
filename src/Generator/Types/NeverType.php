<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * The never type, no PHP equivalent
 */
class NeverType extends PrimitiveType
{
    protected string $type = "never";
}
