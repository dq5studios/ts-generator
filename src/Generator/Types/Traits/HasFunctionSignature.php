<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

use DQ5Studios\TypeScript\Generator\Tokens\FunctionSignatureToken;
use DQ5Studios\TypeScript\Generator\Tokens\MemberToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Types\VoidType;

/**
 * Function signature
 */
trait HasFunctionSignature
{
    /**
     * @param class-string<Type>|Type|Type::* $types
     */
    public function addCallableSignature(string|Type ...$types): MemberToken
    {
        $type = [] === $types ? new VoidType() : array_pop($types);
        $name = FunctionSignatureToken::of("callable", ...$types)->hasCallable(true);

        return $this->addProperty($name, $type);
    }

    /**
     * @param class-string<Type>|Type|Type::* $types
     */
    public function addConstructorSignature(string|Type ...$types): MemberToken
    {
        // TODO: Make sure no property named 'this'
        $type = [] === $types ? new VoidType() : array_pop($types);
        $name = FunctionSignatureToken::of("constructor", ...$types)->hasConstructor(true);

        return $this->addProperty($name, $type);
    }

    /**
     * @param class-string<Type>|Type|Type::* $types
     */
    public function addMethodSignature(string|NameToken $label, string|Type ...$types): MemberToken
    {
        $type = [] === $types ? new VoidType() : array_pop($types);
        $name = FunctionSignatureToken::of($label, ...$types)->hasMethod(true);

        return $this->addProperty($name, $type);
    }
}
