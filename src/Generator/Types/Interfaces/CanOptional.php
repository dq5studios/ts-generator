<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Interfaces;

/**
 * Indicated thing is optional
 */
interface CanOptional
{
    public function isOptional(): bool;
    public function setOptional(bool $optional): self;
}
