<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanComment;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanName;
use DQ5Studios\TypeScript\Generator\Types\NoneType;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasComment;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasName;
use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Values\NoneValue;
use DQ5Studios\TypeScript\Generator\Values\Value;

/**
 * A member of a ContainerType
 */
class MemberToken implements CanName, CanComment
{
    use HasComment;
    use HasName;

    protected Type $type;
    protected Value $value;

    public function __construct(NameToken|null $name = null, Type|null $type = null, Value|null $value = null)
    {
        $this->name = $name;
        if (null === $type) {
            $type = new NoneType();
        }
        $this->type = $type;
        if (null === $value) {
            $value = new NoneValue();
        }
        $this->value = $value;
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
}
