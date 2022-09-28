<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

use DQ5Studios\TypeScript\Generator\Types\PrimitiveType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Values\BooleanValue;
use DQ5Studios\TypeScript\Generator\Values\NullValue;
use DQ5Studios\TypeScript\Generator\Values\NumberValue;
use DQ5Studios\TypeScript\Generator\Values\StringValue;
use DQ5Studios\TypeScript\Generator\Values\UndefinedValue;

/**
 * Value can be used as a type
 */
trait LiteralType
{
    public function asLiteral(): Type
    {
        $type = new class () extends PrimitiveType {
            protected string $type = "";
        };
        /** @psalm-suppress RedundantCast */
        $type->setType(
            match (true) {
                $this instanceof BooleanValue => $this->getValue() ? "true" : "false",
                $this instanceof NullValue => "null",
                $this instanceof NumberValue => (string) $this->getValue(),
                $this instanceof StringValue => "\"{$this->getValue()}\"",
                $this instanceof UndefinedValue => "undefined",
            }
        );

        return $type;
    }
}
