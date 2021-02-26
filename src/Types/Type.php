<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * This is a type
 *
 * @psalm-consistent-constructor
 */
abstract class Type
{
    protected string $type;

    public function __toString(): string
    {
        return $this->type;
    }
}
