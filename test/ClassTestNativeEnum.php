<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript;

if (PHP_VERSION_ID < 80100) {
    return;
}

/**
 * Number registration status
 */
enum ClassTestNativeEnum
{
    /** @var int Unknown */
    case unknown;
    /** @var int Pending */
    case pending;
    /** @var int Approved */
    case approved;
    /** @var int Removed */
    case removed;
}
