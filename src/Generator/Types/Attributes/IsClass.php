<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Attributes;

use Attribute;
use DQ5Studios\TypeScript\Generator\Tokens\CommentToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;

/**
 * The class type, equivalent to PHP class
 */
#[Attribute(Attribute::TARGET_CLASS)]
class IsClass
{
    public function __construct(
        public string|null $name = null,
        public string|null $comment = null,
        public bool|null $export = null,
        public bool|null $ambient = null,
    ) {
    }
}
