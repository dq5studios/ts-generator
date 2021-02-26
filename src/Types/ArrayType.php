<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * The array type, equivalent to PHP array
 */
class ArrayType extends MultiType
{
    protected string $type = "array";
    protected static string $sep = "|";

    public function __toString(): string
    {
        $types = match (count($this->is)) {
            0 => "",
            1 => $this->is[0],
            default => "(" . join(self::$sep, $this->is) . ")",
        };
        return $types . "[]";
    }
}
