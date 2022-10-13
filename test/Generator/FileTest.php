<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Attributes;

use DQ5Studios\TypeScript\Generator\File;
use DQ5Studios\TypeScript\Generator\Types\ClassType;
use DQ5Studios\TypeScript\Generator\Types\EnumType;
use DQ5Studios\TypeScript\Generator\Types\FunctionType;
use DQ5Studios\TypeScript\Generator\Types\InterfaceType;
use DQ5Studios\TypeScript\Generator\Types\NumberType;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\File
 */
class FileTest extends TestCase
{
    public function testAddClass(): void
    {
        $file = new File();
        $file->addClass("skimbleshanks");
        $file->addClass("macavity");
        $this->assertContainsOnlyInstancesOf(ClassType::class, $file->getContents());
    }

    public function testAddEnum(): void
    {
        $file = new File();
        $file->addEnum("skimbleshanks");
        $file->addEnum("macavity");
        $this->assertContainsOnlyInstancesOf(EnumType::class, $file->getContents());
    }

    public function testAddFunction(): void
    {
        $file = new File();
        $file->addFunction("skimbleshanks");
        $file->addFunction("macavity");
        $this->assertContainsOnlyInstancesOf(FunctionType::class, $file->getContents());
    }

    public function testAddInterface(): void
    {
        $file = new File();
        $file->addInterface("skimbleshanks");
        $file->addInterface("macavity");
        $this->assertContainsOnlyInstancesOf(InterfaceType::class, $file->getContents());
    }

    public function testAppend(): void
    {
        $file = new File();
        $file->append(Type::from(Type::NUMBER))->append(Type::from(Type::STRING));
        $actual = $file->getContents();
        $this->assertInstanceOf(NumberType::class, $actual[0]);
        $this->assertInstanceOf(StringType::class, $actual[1]);
    }
}
