<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Types;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Types\AnyType;
use DQ5Studios\TypeScript\Generator\Types\BigIntType;
use DQ5Studios\TypeScript\Generator\Types\BooleanType;
use DQ5Studios\TypeScript\Generator\Types\NeverType;
use DQ5Studios\TypeScript\Generator\Types\NullType;
use DQ5Studios\TypeScript\Generator\Types\NumberType;
use DQ5Studios\TypeScript\Generator\Types\PrimitiveType;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use DQ5Studios\TypeScript\Generator\Types\SymbolType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Types\UndefinedType;
use DQ5Studios\TypeScript\Generator\Types\UnknownType;
use DQ5Studios\TypeScript\Generator\Types\VoidType;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AnyType::class)]
#[CoversClass(BigIntType::class)]
#[CoversClass(BooleanType::class)]
#[CoversClass(NeverType::class)]
#[CoversClass(NullType::class)]
#[CoversClass(NumberType::class)]
#[CoversClass(StringType::class)]
#[CoversClass(SymbolType::class)]
#[CoversClass(Type::class)]
#[CoversClass(UndefinedType::class)]
#[CoversClass(UnknownType::class)]
#[CoversClass(VoidType::class)]
class PrimitiveTypeTest extends TestCase
{
    public static function typeList(): Generator
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
     * @param class-string<Type> $class
     */
    #[DataProvider(typeList::class)]
    public function testToString(string $class, string $as_string, string $expected): void
    {
        $type = new $class();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertInstanceOf(PrimitiveType::class, $type);
        $this->assertSame($expected, Printer::print($type));
        $type = Type::from($as_string);
        $this->assertInstanceOf(Type::class, $type);
        $this->assertInstanceOf(PrimitiveType::class, $type);
        $this->assertSame($expected, Printer::print($type));
    }
}
