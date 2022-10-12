<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Attributes;

use Attribute;

/**
 * Comment for type
 */
#[Attribute(Attribute::TARGET_CLASS)]
class IsComment
{
    public function __construct(
        public string $comment,
    ) {
    }
}
