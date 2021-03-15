<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Interfaces;

/**
 * Indicated thing is readonly
 */
interface CanReadonly
{
    public function isReadonly(): bool;
    public function hasReadonly(bool $readonly = true): self;
}
