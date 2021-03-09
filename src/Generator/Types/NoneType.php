<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * A meta type to indicate one hasn't been assigned
 *
 * @internal
 */
class NoneType extends PrimitiveType
{
    protected string $type = "";
}
