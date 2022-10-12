<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Attributes;

use Attribute;

/**
 * Is this type export
 */
#[Attribute(Attribute::TARGET_CLASS)]
class IsExport
{
    public function __construct(
        public bool $export = true,
    ) {
    }
}
