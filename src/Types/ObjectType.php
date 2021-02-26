<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * The object type, equivalent to PHP object
 */
class ObjectType extends ComplexType
{
    protected string $type = "object";
}
