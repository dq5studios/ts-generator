<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Attributes;

use Attribute;

/**
 * Is this type ambient
 */
#[Attribute(Attribute::TARGET_CLASS)]
class IsAmbient
{
    public function __construct(
        public bool $ambient = true,
    ) {
    }
}
