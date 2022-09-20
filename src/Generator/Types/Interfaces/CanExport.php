<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Interfaces;

/**
 * Exportable
 */
interface CanExport
{
    public function isExport(): bool;

    public function hasExport(bool $enable = true): self;
}
