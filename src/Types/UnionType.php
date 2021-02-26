<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * The union type, represents one of several types
 */
class UnionType extends MultiType
{
    protected static string $sep = " | ";
}
