<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use Generator;
use PHPUnit\Framework\TestCase;

class PrimitiveTypeTest extends TestCase
{
    public function typeList(): Generator
    {
        yield "Any" => [AnyType::class, "any"];
        yield "BitInt" => [BigIntType::class, "bigint"];
        yield "Boolean" => [BooleanType::class, "boolean"];
        yield "Never" => [NeverType::class, "never"];
        yield "Null" => [NullType::class, "null"];
        yield "Number" => [NumberType::class, "number"];
        yield "String" => [StringType::class, "string"];
        yield "Symbol" => [SymbolType::class, "symbol"];
        yield "Undefined" => [UndefinedType::class, "undefined"];
        yield "Unknown" => [UnknownType::class, "unknown"];
        yield "Void" => [VoidType::class, "void"];
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Types\AnyType
     * @covers \DQ5Studios\TypeScript\Generator\Types\BigIntType
     * @covers \DQ5Studios\TypeScript\Generator\Types\BooleanType
     * @covers \DQ5Studios\TypeScript\Generator\Types\NeverType
     * @covers \DQ5Studios\TypeScript\Generator\Types\NullType
     * @covers \DQ5Studios\TypeScript\Generator\Types\NumberType
     * @covers \DQ5Studios\TypeScript\Generator\Types\StringType
     * @covers \DQ5Studios\TypeScript\Generator\Types\SymbolType
     * @covers \DQ5Studios\TypeScript\Generator\Types\UndefinedType
     * @covers \DQ5Studios\TypeScript\Generator\Types\UnknownType
     * @covers \DQ5Studios\TypeScript\Generator\Types\VoidType
     * @covers \DQ5Studios\TypeScript\Generator\Types\Type
     * @dataProvider typeList
     * @param class-string<Type> $class
     */
    public function testToString(string $class, string $expected): void
    {
        $type = new $class();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertInstanceOf(PrimitiveType::class, $type);
        $this->assertSame($expected, (string) $type);
    }
}
