<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use InvalidArgumentException;

/**
 * Attaches a name
 */
trait HasName
{
    protected NameToken | null $name = null;

    public function addName(string | NameToken $name): NameToken
    {
        $this->name = NameToken::from($name);
        return $this->name;
    }

    public function getName(): NameToken
    {
        if (is_null($this->name)) {
            throw new InvalidArgumentException("Name has not been set yet");
        }
        return $this->name;
    }

    public function setName(string | NameToken $name): self
    {
        $this->name = NameToken::from($name);
        return $this;
    }
}
