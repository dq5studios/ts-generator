<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Interfaces;

/**
 * Ambient
 */
interface CanAmbient
{
    public function isAmbient(): bool;
    public function setAmbient(bool $enable): self;
}
