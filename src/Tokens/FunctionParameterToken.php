<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Values\NoneValue;
use UnexpectedValueException;

/**
 * A function signature
 */
class FunctionParameterToken extends MemberToken
{
    /**
     * @param Type|class-string<Type> $type
     * @throws UnexpectedValueException
     */
    public static function from(string | NameToken $name, string | Type $type): self
    {
        if (is_string($name)) {
            $name = new NameToken($name);
        }
        if (is_string($type)) {
            if (!is_subclass_of($type, Type::class)) {
                throw new UnexpectedValueException();
            }
            $type = new $type();
        }

        return new self($name, $type, (new NoneValue()));
    }
}
