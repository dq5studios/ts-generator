<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Interfaces;

/**
 * Indicated thing can use spread syntax
 */
interface CanSpread
{
    public function isSpread(): bool;
    public function setSpread(bool $spread): self;
}
