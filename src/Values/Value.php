<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Types\UndefinedType;

/**
 * This is a value
 *
 * @template T
 */
abstract class Value
{
    /** @var class-string<T> */
    protected string $type = UndefinedType::class;

    /**
     * @return class-string<T>
     */
    public function getType(): string
    {
        return $this->type;
    }

    abstract public function __toString(): string;
}
