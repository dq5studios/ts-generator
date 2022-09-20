<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Interfaces;

use DQ5Studios\TypeScript\Generator\Tokens\VisibilityToken;

/**
 * Attaches a visibility
 */
interface CanVisibility
{
    public function getVisibility(): ?VisibilityToken;

    public function setVisibility(int $visibility): self;
}
