<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests;

use DQ5Studios\TypeScript\Generator\Attributes\IsExport;

if (\PHP_VERSION_ID < 80100) {
    return;
}

/**
 * Some cats you may know
 */
#[IsExport]
enum ClassTestNativeBackedEnum: int
{
    /** @var int skimbleshanks */
    case skimbleshanks = 1;
    /** @var int mungojerrie */
    case mungojerrie = 2;
    /** @var int rumpelteazer */
    case rumpelteazer = 3;
    /** @var int macavity */
    case macavity = 4;
}
