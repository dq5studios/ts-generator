<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use Generator;
use PHPUnit\Framework\TestCase;

class ComplexTypeTest extends TestCase
{
    public function typeList(): Generator
    {
        yield "Function" => [FunctionType::class, "Function"];
        yield "Object" => [ObjectType::class, "object"];
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Types\FunctionType
     * @covers \DQ5Studios\TypeScript\Generator\Types\ObjectType
     *
     * @dataProvider typeList
     *
     * @param class-string<Type> $class
     */
    public function testToString(string $class, string $expected): void
    {
        $type = new $class();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertSame($expected, (string) $type);
    }
}
