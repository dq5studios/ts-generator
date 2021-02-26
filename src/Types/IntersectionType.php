<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * The intersection type, represents the combination of several types
 */
class IntersectionType extends MultiType
{
    protected static string $sep = " & ";
}
