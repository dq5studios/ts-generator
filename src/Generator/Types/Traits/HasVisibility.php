<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

use DQ5Studios\TypeScript\Generator\Tokens\VisibilityToken;

/**
 * Attaches a visibility
 */
trait HasVisibility
{
    /** @var VisibilityToken $visibility */
    protected VisibilityToken | null $visibility = null;

    /** @return VisibilityToken */
    public function getVisibility(): ?VisibilityToken
    {
        return $this->visibility;
    }

    /** @param VisibilityToken::PUBLIC|VisibilityToken::PROTECTED|VisibilityToken::PRIVATE|VisibilityToken $visibility */
    public function setVisibility(int | VisibilityToken $visibility): self
    {
        if ($visibility instanceof VisibilityToken) {
            $this->visibility = $visibility;
        } elseif (!isset($this->visibility)) {
            $this->visibility = new VisibilityToken($visibility);
        } else {
            $this->visibility->set($visibility);
        }
        return $this;
    }
}
