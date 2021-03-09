<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

use DQ5Studios\TypeScript\Generator\Tokens\FunctionSignatureToken;
use DQ5Studios\TypeScript\Generator\Tokens\MemberToken;
use DQ5Studios\TypeScript\Generator\Types\Type;

/**
 * Function signature
 */
trait HasFunctionSignature
{
    /**
     * @param class-string<Type>|Type|Type::* $types
     */
    public function addCallableSignature(string | Type ...$types): MemberToken
    {
        $type = array_pop($types);
        $name = FunctionSignatureToken::of(...$types);
        return $this->addProperty($name, $type);
    }

    /**
     * @param class-string<Type>|Type|Type::* $types
     */
    public function addConstructorSignature(string | Type ...$types): MemberToken
    {
        // TODO: No property named 'this'
        $type = array_pop($types);
        $name = FunctionSignatureToken::of(...$types)->setConstructor(true);
        return $this->addProperty($name, $type);
    }
}
