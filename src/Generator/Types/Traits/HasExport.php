<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

/**
 * Exportable
 */
trait HasExport
{
    protected bool $export = false;

    public function isExport(): bool
    {
        return $this->export;
    }

    public function setExport(bool $enable): self
    {
        $this->export = $enable;
        return $this;
    }
}
