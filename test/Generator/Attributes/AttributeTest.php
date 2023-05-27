<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Attributes;

use DQ5Studios\TypeScript\Generator\Attributes\IsAmbient;
use DQ5Studios\TypeScript\Generator\Attributes\IsClass;
use DQ5Studios\TypeScript\Generator\Attributes\IsComment;
use DQ5Studios\TypeScript\Generator\Attributes\IsConst;
use DQ5Studios\TypeScript\Generator\Attributes\IsEnum;
use DQ5Studios\TypeScript\Generator\Attributes\IsExport;
use DQ5Studios\TypeScript\Generator\Attributes\IsInterface;
use DQ5Studios\TypeScript\Generator\Attributes\IsName;
use DQ5Studios\TypeScript\Generator\Attributes\IsReadonly;
use DQ5Studios\TypeScript\Tests\ClassTestClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

#[CoversClass(IsAmbient::class)]
#[CoversClass(IsClass::class)]
#[CoversClass(IsComment::class)]
#[CoversClass(IsConst::class)]
#[CoversClass(IsEnum::class)]
#[CoversClass(IsExport::class)]
#[CoversClass(IsInterface::class)]
#[CoversClass(IsName::class)]
#[CoversClass(IsReadonly::class)]
class AttributeTest extends TestCase
{
    public function testInterfaceConversionFromString(): void
    {
        $ref = new ReflectionClass(ClassTestClass::class);
        $actual = $ref->getAttributes();
        $this->assertCount(1, $actual);
        $this->assertEquals(IsExport::class, $actual[0]->getName());
    }
}
