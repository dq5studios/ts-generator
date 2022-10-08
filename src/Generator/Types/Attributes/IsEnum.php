<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Attributes;

use Attribute;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;

/**
 * The enum type, equivalent to PHP enum
 */
#[Attribute(Attribute::TARGET_CLASS)]
class IsEnum
{
    public function __construct(
        public string|NameToken|null $name = null,
        public string|null $comment = null,
        public bool|null $export = null,
        public bool|null $ambient = null,
        public bool|null $const = null,
    ) {
    }
}
