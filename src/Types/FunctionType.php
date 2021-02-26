<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use DQ5Studios\TypeScript\Generator\Tokens\FunctionParameterToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use UnexpectedValueException;

/**
 * The function type, equivalent to PHP callable
 */
class FunctionType extends ComplexType
{
    protected string $type = "Function";

    protected string $signature = "";
    /** @var list<FunctionParameterToken> */
    protected array $parameters = [];
    /** @var Type|null */
    protected Type | null $return = null;

    public function setSignature(string $signature): self
    {
        $this->signature = $signature;
        return $this;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    /**
     * @param Type|class-string<Type> $type
     */
    public function addParameter(string | Type $type, string | NameToken $name = null): self
    {
        // TODO: Spread operator

        if (empty($name)) {
            $name = "arg_" . count($this->parameters);
        }
        $member = FunctionParameterToken::from($name, $type);
        $this->parameters[] = $member;
        $this->refreshSignature();
        return $this;
    }

    /**
     * @return list<FunctionParameterToken>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param list<Type|class-string<Type>> $parameters
     */
    public function setParameters(string | Type ...$parameters): self
    {
        $this->parameters = [];
        foreach ($parameters as $parameter) {
            $this->addParameter($parameter);
        }
        $this->refreshSignature();
        return $this;
    }

    public function getReturn(): Type | null
    {
        return $this->return;
    }

    /**
     * @param Type|class-string<Type> $return
     * @throws UnexpectedValueException
     */
    public function setReturn(string | Type $return): self
    {
        if ($return instanceof Type) {
            $this->return = $return;
        } else {
            if (!is_subclass_of($return, Type::class)) {
                throw new UnexpectedValueException();
            }
            $this->return = new $return();
        }
        $this->refreshSignature();
        return $this;
    }

    private function refreshSignature(): void
    {
        $parameters = [];
        foreach ($this->parameters as $parameter) {
            $parameters[] = "{$parameter->getName()}: {$parameter->getType()}";
        }
        $this->signature = "(" . implode(", ", $parameters) . ") => " . (string) ($this->return ?? new VoidType());
    }

    public function __toString(): string
    {
        if (empty($this->signature)) {
            return parent::__toString();
        }

        return $this->signature;
    }
}
