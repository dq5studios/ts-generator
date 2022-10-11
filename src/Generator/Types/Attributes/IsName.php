<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Attributes;

use Attribute;

/**
 * Name of type
 */
#[Attribute(Attribute::TARGET_CLASS)]
class IsName
{
    public function __construct(
        public string $name,
    ) {
    }
}
