<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

/**
 * Indicated thing is static
 */
trait HasStatic
{
    protected bool $static = false;

    public function isStatic(): bool
    {
        return $this->static;
    }

    public function hasStatic(bool $static = true): self
    {
        $this->static = $static;

        return $this;
    }
}
