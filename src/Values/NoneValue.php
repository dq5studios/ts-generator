<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Types\NoneType;

/**
 * A meta value to indicate one hasn't been assigned
 *
 * @template T as NoneType
 * @extends Value<T>
 *
 * @internal
 */
class NoneValue extends Value
{
    /** @var class-string<T> */
    protected string $type = NoneType::class;

    /**
     * @internal
     */
    public function __toString(): string
    {
        return "";
    }
}
