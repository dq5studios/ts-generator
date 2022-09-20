<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Interfaces;

use DQ5Studios\TypeScript\Generator\Tokens\MemberToken;
use DQ5Studios\TypeScript\Generator\Types\Type;

/**
 * Index signature
 */
interface CanIndexSignature
{
    /**
     * @param class-string<Type>|Type|Type::* $index
     * @param class-string<Type>|Type|Type::* $type
     */
    public function addIndexSignature(string|Type $index, string|Type $type): MemberToken;
}
