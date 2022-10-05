<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests;

if (\PHP_VERSION_ID < 80100) {
    return;
}

/**
 * Some cats you may know
 */
enum ClassTestNativeEnum
{
    /** @var int skimbleshanks */
    case skimbleshanks;
    /** @var int mungojerrie */
    case mungojerrie;
    /** @var int rumpelteazer */
    case rumpelteazer;
    /** @var int macavity */
    case macavity;
}
