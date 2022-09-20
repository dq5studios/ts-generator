<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

/**
 * Visibility level of an item
 */
class VisibilityToken
{
    public const PUBLIC = 1;
    public const PROTECTED = 2;
    public const PRIVATE = 4;

    /** @param self::PUBLIC|self::PROTECTED|self::PRIVATE $visibility */
    public function __construct(protected int $visibility = self::PUBLIC)
    {
    }

    /** @return self::PUBLIC|self::PROTECTED|self::PRIVATE */
    public function get(): int
    {
        return $this->visibility;
    }

    /** @param self::PUBLIC|self::PROTECTED|self::PRIVATE $visibility */
    public function set(int $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }
}
