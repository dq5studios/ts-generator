<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript;

use DQ5Studios\TypeScript\Generator\Types\EnumType;

/**
 * Number registration status
 */
#[EnumType]
class ClassTestEnum
{
    /** Unknown */
    private const unknown = 0;
    /** Pending */
    private const pending = 1;
    /** Approved */
    private const approved = 2;
    /** Removed */
    private const removed = 3;
}
