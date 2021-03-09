<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use Generator;
use PHPUnit\Framework\TestCase;

class PrimitiveTypeTest extends TestCase
{
    public function typeList(): Generator
    {
        yield "Any" => [AnyType::class, Type::ANY, "any"];
        yield "BitInt" => [BigIntType::class, Type::BIGINT, "bigint"];
        yield "Boolean" => [BooleanType::class, Type::BOOLEAN, "boolean"];
        yield "Never" => [NeverType::class, Type::NEVER, "never"];
        yield "Null" => [NullType::class, Type::NULL, "null"];
        yield "Number" => [NumberType::class, Type::NUMBER, "number"];
        yield "String" => [StringType::class, Type::STRING, "string"];
        yield "Symbol" => [SymbolType::class, Type::SYMBOL, "symbol"];
        yield "Undefined" => [UndefinedType::class, Type::UNDEFINED, "undefined"];
        yield "Unknown" => [UnknownType::class, Type::UNKNOWN, "unknown"];
        yield "Void" => [VoidType::class, Type::VOID, "void"];
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
     * @covers \DQ5Studios\TypeScript\Generator\Types\Type
     * @covers \DQ5Studios\TypeScript\Generator\Types\UndefinedType
     * @covers \DQ5Studios\TypeScript\Generator\Types\UnknownType
     * @covers \DQ5Studios\TypeScript\Generator\Types\VoidType
     * @dataProvider typeList
     * @param class-string<Type> $class
     */
    public function testToString(string $class, string $as_string, string $expected): void
    {
        $type = new $class();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertInstanceOf(PrimitiveType::class, $type);
        $this->assertSame($expected, (string) $type);
        $type = Type::from($as_string);
        $this->assertInstanceOf(Type::class, $type);
        $this->assertInstanceOf(PrimitiveType::class, $type);
        $this->assertSame($expected, (string) $type);
    }
}
