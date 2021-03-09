<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

/**
 * Ambient
 */
trait HasAmbient
{
    protected bool $ambient = false;

    public function isAmbient(): bool
    {
        return $this->ambient;
    }

    public function setAmbient(bool $enable): self
    {
        $this->ambient = $enable;
        return $this;
    }
}
