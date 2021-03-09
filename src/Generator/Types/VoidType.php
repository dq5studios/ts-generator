<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * The void type, equivalent to PHP void
 */
class VoidType extends PrimitiveType
{
    protected string $type = "void";
}
