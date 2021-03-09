<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanParameters;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasParameters;
use DQ5Studios\TypeScript\Generator\Types\Type;

/**
 * A function signature
 */
class FunctionSignatureToken extends NameToken implements CanParameters
{
    use HasParameters;

    protected bool $constructor = false;

    /**
     * @param class-string<Type>|Type|Type::* $types
     */
    public static function of(string | Type ...$types): self
    {
        $fn = new self("fn");
        $fn->setParameters($types);
        return $fn;
    }

    public function isConstructor(): bool
    {
        return $this->constructor;
    }

    public function setConstructor(bool $constructor): self
    {
        $this->constructor = $constructor;
        return $this;
    }
}
