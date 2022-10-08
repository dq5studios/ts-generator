<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Attributes;

use Attribute;
use DQ5Studios\TypeScript\Generator\Tokens\CommentToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;

/**
 * The interface type, no PHP equivalent
 */
#[Attribute(Attribute::TARGET_CLASS)]
class IsInterface
{
    public function __construct(
        public string|NameToken|null $name = null,
        public string|CommentToken|null $comment = null,
        public bool|null $export = null,
        public bool|null $ambient = null,
    ) {
    }
}
