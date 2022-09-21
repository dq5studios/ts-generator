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

    /** @param VisibilityToken::PUBLIC|VisibilityToken::PROTECTED|VisibilityToken::PRIVATE|VisibilityToken $visibility */
    public function setVisibility(int|VisibilityToken $visibility): self;
}
