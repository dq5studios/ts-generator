<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanParameters;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasParameters;

/**
 * The function type, equivalent to PHP callable
 */
class FunctionType extends ComplexType implements CanParameters
{
    use HasParameters;

    protected string $type = "Function";

    protected Type|null $return = null;

    public function isSignature(): bool
    {
        return null !== $this->parameters || null !== $this->return;
    }

    public function getReturn(): Type
    {
        return $this->return ?? (new VoidType());
    }

    /**
     * @param class-string<Type>|Type|Type::* $return
     */
    public function setReturn(string|Type $return): self
    {
        $this->return = Type::from($return);

        return $this;
    }
}
