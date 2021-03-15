<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

/**
 * Indicated thing can use spread syntax
 */
trait HasSpread
{
    protected bool $spread = false;

    public function isSpread(): bool
    {
        return $this->spread;
    }

    public function hasSpread(bool $spread = true): self
    {
        $this->spread = $spread;
        return $this;
    }
}
