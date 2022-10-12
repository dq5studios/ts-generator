<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Attributes;

use Attribute;

/**
 * Is this type const
 */
#[Attribute(Attribute::TARGET_CLASS)]
class IsConst
{
    public function __construct(
        public bool $const = true,
    ) {
    }
}
