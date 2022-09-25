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

    protected bool $callable = false;
    protected bool $constructor = false;
    protected bool $method = false;

    /**
     * @param class-string<Type>|Type|Type::* ...$types
     */
    public static function of(string|NameToken $label, string|Type ...$types): self
    {
        if ($label instanceof NameToken) {
            $label = Printer::print($label);
        }
        $fn = new self($label);
        $fn->setParameters($types);

        return $fn;
    }

    public function isCallable(): bool
    {
        return $this->callable;
    }

    public function hasCallable(bool $callable = true): self
    {
        $this->callable = $callable;

        return $this;
    }

    public function isConstructor(): bool
    {
        return $this->constructor;
    }

    public function hasConstructor(bool $constructor = true): self
    {
        $this->constructor = $constructor;

        return $this;
    }

    public function isMethod(): bool
    {
        return $this->method;
    }

    public function hasMethod(bool $method = true): self
    {
        $this->method = $method;

        return $this;
    }
}
