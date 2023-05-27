<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Values\NoneValue;
use DQ5Studios\TypeScript\Generator\Values\NumberValue;
use DQ5Studios\TypeScript\Generator\Values\StringValue;
use DQ5Studios\TypeScript\Generator\Values\Value;
use InvalidArgumentException;

use function count;
use function is_float;
use function is_int;
use function is_string;

/**
 * A member for an Enum
 */
class EnumMemberToken extends MemberToken
{
    /**
     * @throws InvalidArgumentException
     */
    public static function from(string|NameToken $name, int|float|string|Value ...$value): self
    {
        if (count($value) > 1) {
            throw new InvalidArgumentException("No more that one value can be assigned at a time");
        }

        $name = NameToken::from($name);

        $typed_value = match (true) {
            empty($value) => new NoneValue(),
            is_int($value[0]) || is_float($value[0]) => new NumberValue($value[0]),
            is_string($value[0]) => new StringValue($value[0]),
            $value[0] instanceof NumberValue,
            $value[0] instanceof StringValue,
            $value[0] instanceof NoneValue => $value[0],
            default => throw new InvalidArgumentException("Computed members not supported yet"),
        };

        return new self(value: $typed_value, name: $name);
    }
}
