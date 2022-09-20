<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

/**
 * Indicated thing is readonly
 */
trait HasReadonly
{
    protected bool $readonly = false;

    public function isReadonly(): bool
    {
        return $this->readonly;
    }

    public function hasReadonly(bool $readonly = true): self
    {
        $this->readonly = $readonly;

        return $this;
    }
}
