<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Attributes;

use Attribute;

/**
 * Is this property readonly
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class IsReadonly
{
    public function __construct(
        public bool $readonly = true,
    ) {
    }
}
