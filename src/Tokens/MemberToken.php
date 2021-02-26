<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Types\NoneType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Values\NoneValue;
use DQ5Studios\TypeScript\Generator\Values\Value;

/**
 * A member of a ContainerType
 */
class MemberToken
{
    public function __construct(protected NameToken $name, protected Type $type, protected Value $value)
    {
    }

    public function getName(): NameToken
    {
        return $this->name;
    }

    public function setName(NameToken $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function setType(Type $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getValue(): Value
    {
        return $this->value;
    }

    public function setValue(Value $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function __toString(): string
    {
        $output = (string) $this->name;
        if (!($this->type instanceof NoneType)) {
            $output .= ": " . (string) $this->type;
        }
        if (!($this->value instanceof NoneValue)) {
            $output .= " = " . (string) $this->value;
        }
        return $output;
    }
}
