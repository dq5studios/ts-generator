<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript;

if (PHP_VERSION_ID < 80100) {
    return;
}

/**
 * Number registration status
 */
enum ClassTestNativeBackedEnum: int
{
    /** @var int Unknown */
    case unknown = 0;
    /** @var int Pending */
    case pending = 1;
    /** @var int Approved */
    case approved = 2;
    /** @var int Removed */
    case removed = 3;
}
