<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Interfaces;

use DQ5Studios\TypeScript\Generator\Tokens\NameToken;

/**
 * Attaches a name
 */
interface CanName
{
    public function addName(string | NameToken $name): NameToken;
    public function getName(): NameToken;
    public function setName(string | NameToken $name): self;
}
