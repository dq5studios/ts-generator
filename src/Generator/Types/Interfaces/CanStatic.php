<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Interfaces;

/**
 * Indicated thing is static
 */
interface CanStatic
{
    public function isStatic(): bool;

    public function hasStatic(bool $static = true): self;
}
