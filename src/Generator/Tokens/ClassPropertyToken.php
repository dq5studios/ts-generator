<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanOptional;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanReadonly;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasOptional;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasReadonly;
use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Values\NoneValue;
use DQ5Studios\TypeScript\Generator\Values\Value;

/**
 * A class property
 */
class ClassPropertyToken extends MemberToken implements CanOptional, CanReadonly
{
    use HasOptional;
    use HasReadonly;

    public const VISIBILITY = [
        "public" => 1,
        "protected" => 2,
        "private" => 3,
    ];

    /**
     * @param class-string<Type>|Type|Type::* $type
     */
    public static function from(string | NameToken $name, string | Type $type, mixed $value = null): self
    {
        // TODO: Use spread operator since value is option
        if (is_null($value)) {
            $value = new NoneValue();
        }
        $optional = false;
        if (is_string($name)) {
            if (substr($name, -1) === "?") {
                $optional = true;
                $name = rtrim($name, "?");
            }
        }
        $name = NameToken::from($name);
        $type = Type::from($type);
        $value = Value::from($value);

        return (new self(type: $type, name: $name, value: $value))->hasOptional($optional);
    }

    // TODO: Set readonly
    // TODO: Set visibility
    // TODO: Set get/set
    // TODO: construtor
    // TODO: static
}
