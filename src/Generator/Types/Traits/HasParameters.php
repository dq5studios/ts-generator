<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

use DQ5Studios\TypeScript\Generator\Tokens\FunctionParameterToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\Type;

use function count;

/**
 * Indicated thing is optional
 */
trait HasParameters
{
    /** @var array<string,FunctionParameterToken> */
    protected array|null $parameters = null;

    /**
     * @param class-string<Type>|Type|Type::* $type
     */
    public function addParameter(string|Type $type, string|NameToken $name = null): FunctionParameterToken
    {
        if (null === $this->parameters) {
            $this->parameters = [];
        }
        if (empty($name)) {
            $name = "arg_" . count($this->parameters);
        }
        $member = FunctionParameterToken::from($name, $type);

        return $this->parameters[$member->getName()->getName()] = $member;
    }

    /**
     * @return array<string,FunctionParameterToken>
     */
    public function getParameters(): array
    {
        return $this->parameters ?? [];
    }

    /**
     * @param array<array-key,class-string<Type>|Type|Type::*> $parameters
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = [];
        foreach ($parameters as $parameter) {
            $this->addParameter($parameter);
        }

        return $this;
    }
}
