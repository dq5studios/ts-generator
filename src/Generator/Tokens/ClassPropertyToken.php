<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Types\ArrayType;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanOptional;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanReadonly;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanStatic;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanVisibility;
use DQ5Studios\TypeScript\Generator\Types\ObjectType;
use DQ5Studios\TypeScript\Generator\Types\PrimitiveType;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasOptional;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasReadonly;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasStatic;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasVisibility;
use DQ5Studios\TypeScript\Generator\Types\TupleType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Values\NoneValue;
use DQ5Studios\TypeScript\Generator\Values\Value;

/**
 * A class property
 */
class ClassPropertyToken extends MemberToken implements CanOptional, CanReadonly, CanStatic, CanVisibility
{
    use HasOptional;
    use HasReadonly;
    use HasStatic;
    use HasVisibility;

    /**
     * @param class-string<Type>|Type|Type::*                                                             $type
     * @param VisibilityToken::PUBLIC|VisibilityToken::PROTECTED|VisibilityToken::PRIVATE|VisibilityToken $visibility
     */
    public static function from(
        string|NameToken $name,
        string|Type $type,
        mixed $value = null,
        int|VisibilityToken $visibility = null,
        bool $readonly = false,
        bool $optional = false,
        bool $static = false,
    ): self {
        if (is_null($value)) {
            $value = new NoneValue();
        }
        if (is_string($name)) {
            if (str_ends_with($name, "?")) {
                $optional = true;
                $name = rtrim($name, "?");
            }
        }
        $name = NameToken::from($name);
        $type = Type::from($type);
        $value = Value::from($value);

        $class = (new self(type: $type, name: $name, value: $value))
            ->hasStatic($static)
            ->hasOptional($optional);
        if (!is_null($visibility)) {
            $class->setVisibility($visibility);
        }
        if (
            $type instanceof ArrayType ||
            $type instanceof ObjectType ||
            $type instanceof PrimitiveType ||
            $type instanceof TupleType
        ) {
            // TODO: check if static, name isn't name, length, call
            $class->hasReadonly($readonly);
        }

        return $class;
    }

    // TODO: Set get/set
    // TODO: construtor
}
