<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Interfaces;

use DQ5Studios\TypeScript\Generator\Tokens\MemberToken;
use DQ5Studios\TypeScript\Generator\Types\Type;

/**
 * Function signature
 */
interface CanFunctionSignature
{
    /**
     * @param class-string<Type>|Type|Type::* $types
     */
    public function addCallableSignature(string | Type ...$types): MemberToken;
    /**
     * @param class-string<Type>|Type|Type::* $types
     */
    public function addConstructorSignature(string | Type ...$types): MemberToken;
}
