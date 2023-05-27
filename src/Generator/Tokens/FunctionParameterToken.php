<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Types\ArrayType;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanOptional;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanSpread;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasOptional;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasSpread;
use DQ5Studios\TypeScript\Generator\Types\TupleType;
use DQ5Studios\TypeScript\Generator\Types\Type;

use function is_string;

/**
 * A function signature
 */
class FunctionParameterToken extends MemberToken implements CanOptional, CanSpread
{
    use HasOptional;
    use HasSpread;

    /**
     * @param class-string<Type>|Type|Type::* $type
     */
    public static function from(string|NameToken $name, string|Type $type): self
    {
        $type = Type::from($type);
        $spread = false;
        $optional = false;
        if (is_string($name)) {
            if (($type instanceof ArrayType || $type instanceof TupleType) && str_starts_with($name, "...")) {
                $spread = true;
                $name = ltrim($name, ".");
            }
            if (str_ends_with($name, "?")) {
                $optional = true;
                $name = rtrim($name, "?");
            }
        }
        $name = NameToken::from($name);

        return (new self(type: $type, name: $name))->hasOptional($optional)->hasSpread($spread);
    }
}
