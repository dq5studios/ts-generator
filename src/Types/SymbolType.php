<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * The symbol type, no PHP equivalent
 */
class SymbolType extends PrimitiveType
{
    protected string $type = "symbol";
}
