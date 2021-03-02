<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use DQ5Studios\TypeScript\Generator\Tokens\FunctionParameterToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;

/**
 * The function type, equivalent to PHP callable
 */
class FunctionType extends ComplexType
{
    protected string $type = "Function";

    /** @var array<string,FunctionParameterToken> */
    protected array | null $parameters = null;
    /** @var Type|null */
    protected Type | null $return = null;

    public function getSignature(): string
    {
        $signature = "";
        if (!is_null($this->parameters) || !is_null($this->return)) {
            $signature = "("
                . implode(", ", $this->parameters ?? [])
                . ")";
            $signature .= " => ";
            $signature .= (string) ($this->return ?? new VoidType());
        }
        return $signature;
    }

    /**
     * @param class-string<Type>|Type|Type::* $type
     */
    public function addParameter(string | Type $type, string | NameToken $name = null): FunctionParameterToken
    {
        if (is_null($this->parameters)) {
            $this->parameters = [];
        }
        if (empty($name)) {
            $name = "arg_" . count($this->parameters);
        }
        $member = FunctionParameterToken::from($name, $type);
        return $this->parameters[(string) $member->getName()] = $member;
    }

    /**
     * @return array<string,FunctionParameterToken>
     */
    public function getParameters(): array
    {
        return $this->parameters ?? [];
    }

    /**
     * @param list<class-string<Type>|Type|Type::*> $parameters
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = [];
        foreach ($parameters as $parameter) {
            $this->addParameter($parameter);
        }
        return $this;
    }

    public function getReturn(): Type | null
    {
        return $this->return;
    }

    /**
     * @param class-string<Type>|Type|Type::* $return
     */
    public function setReturn(string | Type $return): self
    {
        $this->return = Type::from($return);
        return $this;
    }

    public function __toString(): string
    {
        if (empty($this->getSignature())) {
            return parent::__toString();
        }

        return $this->getSignature();
    }
}
