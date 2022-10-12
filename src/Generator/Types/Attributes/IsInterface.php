<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Attributes;

use Attribute;

/**
 * The interface type, similar to PHP interface
 */
#[Attribute(Attribute::TARGET_CLASS)]
class IsInterface
{
    public function __construct(
        public string|null $name = null,
        public string|null $comment = null,
        public bool|null $export = null,
        public bool|null $ambient = null,
    ) {
    }
}
