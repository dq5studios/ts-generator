<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Types\Type;
use InvalidArgumentException;

/**
 * A function signature
 */
class FunctionSignatureToken extends NameToken
{
    protected bool $constructor = false;
    /** @var array<string,FunctionParameterToken> */
    protected array $parameters = [];

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

    /**
     * @param class-string<Type>|Type|Type::* $type
     */
    public function addParameter(string | Type $type, string | NameToken $name = null): FunctionParameterToken
    {
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
        return $this->parameters;
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

    public function __toString(): string
    {
        return ($this->constructor ? "new" : "") . "(" . implode(", ", $this->parameters) . ")";
    }
}
