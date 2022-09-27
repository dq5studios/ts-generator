<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanOptional;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanReadonly;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasOptional;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasReadonly;
use DQ5Studios\TypeScript\Generator\Types\Type;

/**
 * A member for an Enum
 */
class InterfacePropertyToken extends MemberToken implements CanOptional, CanReadonly
{
    use HasOptional;
    use HasReadonly;

    /**
     * @param class-string<Type>|Type|Type::* $type
     */
    public static function from(string|NameToken $name, string|Type $type): self
    {
        $optional = false;
        if (is_string($name) && str_ends_with($name, "?")) {
            $optional = true;
            $name = rtrim($name, "?");
        }
        $name = NameToken::from($name);
        $type = Type::from($type);

        return (new self(type: $type, name: $name))->hasOptional($optional);
    }
}
