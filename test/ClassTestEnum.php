<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests;

use DQ5Studios\TypeScript\Generator\Types\Attributes\IsEnum;

/**
 * Some cats you may know
 */
#[IsEnum]
class ClassTestEnum
{
    /** skimbleshanks */
    private const skimbleshanks = 0;
    /** mungojerrie */
    private const mungojerrie = 1;
    /** rumpelteazer */
    private const rumpelteazer = 2;
    /** macavity */
    private const macavity = 3;
}
