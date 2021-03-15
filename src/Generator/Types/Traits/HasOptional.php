<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

/**
 * Indicated thing is optional
 */
trait HasOptional
{
    protected bool $optional = false;

    public function isOptional(): bool
    {
        return $this->optional;
    }

    public function hasOptional(bool $optional = true): self
    {
        $this->optional = $optional;
        return $this;
    }
}
