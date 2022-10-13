<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests;

use DQ5Studios\TypeScript\Generator\Attributes\IsClass;
use DQ5Studios\TypeScript\Generator\Attributes\IsReadonly;

/**
 * An example class
 */
#[IsClass]
class ClassTestExtends extends ClassTestClass
{
    #[IsReadonly]
    public string $readonly_prop;
}
