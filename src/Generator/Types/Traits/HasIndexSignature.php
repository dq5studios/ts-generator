<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

use DQ5Studios\TypeScript\Generator\Tokens\IndexSignatureToken;
use DQ5Studios\TypeScript\Generator\Tokens\MemberToken;
use DQ5Studios\TypeScript\Generator\Types\Type;

/**
 * Index signature
 */
trait HasIndexSignature
{
    /**
     * @param class-string<Type>|Type|Type::* $index
     * @param class-string<Type>|Type|Type::* $type
     */
    public function addIndexSignature(string | Type $index, string | Type $type): MemberToken
    {
        $name = IndexSignatureToken::of($index);
        return $this->addProperty($name, $type);
    }
}
