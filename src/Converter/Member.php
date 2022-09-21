<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Converter;

use Amp\Struct;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Tokens\VisibilityToken;
use DQ5Studios\TypeScript\Generator\Types\Type;

/**
 * @internal
 *
 * @psalm-seal-properties
 */
class Member
{
    use Struct;

    public function __construct(
        public NameToken $name,
        public Type $type,
        /** @var VisibilityToken::PUBLIC|VisibilityToken::PROTECTED|VisibilityToken::PRIVATE */
        public int $visibility,
        public mixed $value = null,
        public ?string $comment = null,
        public ?bool $readonly = null,
    ) {
    }
}
