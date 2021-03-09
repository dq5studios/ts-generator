<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use Exception;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Types\MultiType
 */
class MultiTypeTest extends TestCase
{
    public function typeList(): Generator
    {
        yield "Array" => [ArrayType::class, Type::ARRAY, "[]"];
        yield "Intersection" => [IntersectionType::class, Type::INTERSECTION, ""];
        yield "Tuple" => [TupleType::class, Type::TUPLE, "[]"];
        yield "Union" => [UnionType::class, Type::UNION, ""];
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Types\ArrayType
     * @covers \DQ5Studios\TypeScript\Generator\Types\IntersectionType
     * @covers \DQ5Studios\TypeScript\Generator\Types\TupleType
     * @covers \DQ5Studios\TypeScript\Generator\Types\Type
     * @covers \DQ5Studios\TypeScript\Generator\Types\UnionType
     * @dataProvider typeList
     * @param class-string<Type> $class
     */
    public function testToString(string $class, string $as_string, string $expected): void
    {
        $type = new $class();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertInstanceOf(MultiType::class, $type);
        $this->assertSame($expected, (string) $type);
        $type = Type::from($as_string);
        $this->assertInstanceOf(Type::class, $type);
        $this->assertInstanceOf(MultiType::class, $type);
        $this->assertSame($expected, (string) $type);
    }

    public function singleTypeList(): Generator
    {
        $contains = [StringType::class];
        yield "Array" => [ArrayType::class, $contains, "string[]"];
        yield "Intersection" => [IntersectionType::class, $contains, "string"];
        yield "Tuple" => [TupleType::class, $contains, "[string]"];
        yield "Union" => [UnionType::class, $contains, "string"];
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Types\ArrayType
     * @covers \DQ5Studios\TypeScript\Generator\Types\IntersectionType
     * @covers \DQ5Studios\TypeScript\Generator\Types\TupleType
     * @covers \DQ5Studios\TypeScript\Generator\Types\UnionType
     * @dataProvider singleTypeList
     * @param class-string<Type> $class
     * @param list<class-string<Type>> $contains
     */
    public function testSingleTypeToString(string $class, array $contains, string $expected): void
    {
        $type = new $class();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertInstanceOf(MultiType::class, $type);
        $type->contains(...$contains);
        $this->assertSame($expected, (string) $type);
    }

    public function multiTypeList(): Generator
    {
        $contains = [NumberType::class, new StringType()];
        yield "Array" => [ArrayType::class, $contains, "(number|string)[]"];
        yield "Intersection" => [IntersectionType::class, $contains, "(number & string)"];
        yield "Tuple" => [TupleType::class, $contains, "[number,string]"];
        yield "Union" => [UnionType::class, $contains, "(number | string)"];
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Types\ArrayType
     * @covers \DQ5Studios\TypeScript\Generator\Types\IntersectionType
     * @covers \DQ5Studios\TypeScript\Generator\Types\TupleType
     * @covers \DQ5Studios\TypeScript\Generator\Types\UnionType
     * @dataProvider multiTypeList
     * @param class-string<Type> $class
     * @param list<class-string<Type>> $contains
     */
    public function testMultiTypeToString(string $class, array $contains, string $expected): void
    {
        $type = new $class();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertInstanceOf(MultiType::class, $type);
        $this->assertTrue($type instanceof $class);
        $type->contains(...$contains);
        $this->assertSame($expected, (string) $type);

        $type = $class::of(...$contains);
        $this->assertInstanceOf(Type::class, $type);
        $this->assertInstanceOf(MultiType::class, $type);
        $this->assertInstanceOf($class, $type);
        $this->assertSame($expected, (string) $type);
    }

    public function testBadData(): void
    {
        $type = new ArrayType();
        try {
            /** @psalm-suprress ArgumentTypeCoercion */
            $type->contains("Not a valid type");
            $this->fail("Accepted bad data");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }

    public function testSeperator(): void
    {
        $type = new class extends MultiType{
        };
        $contains = [NumberType::class, new StringType()];
        $type->contains(...$contains);
        $type->setSeperator("??");
        $this->assertSame("(number??string)", (string) $type);
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Types\Type
     */
    public function testFrom(): void
    {
        $actual = Type::from("number|string");
        $this->assertInstanceOf(UnionType::class, $actual);
        $this->assertSame("(number | string)", (string) $actual);

        $actual = Type::from("number&string");
        $this->assertInstanceOf(IntersectionType::class, $actual);
        $this->assertSame("(number & string)", (string) $actual);
    }
}
