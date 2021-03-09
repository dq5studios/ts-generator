<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Interfaces;

use DQ5Studios\TypeScript\Generator\Tokens\FunctionParameterToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\Type;

/**
 * Indicated thing is optional
 */
interface CanParameters
{
    /** @param class-string<Type>|Type|Type::* $type */
    public function addParameter(string | Type $type, string | NameToken $name = null): FunctionParameterToken;
    public function getParameters(): array;
    /** @param list<class-string<Type>|Type|Type::*> $parameters */
    public function setParameters(array $parameters): self;
}
