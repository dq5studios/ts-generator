<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use Exception;
use Generator;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Types\MultiType
 */
class MultiTypeTest extends TestCase
{
    public function typeList(): Generator
    {
        yield "Array" => [ArrayType::class, "[]"];
        yield "Intersection" => [IntersectionType::class, ""];
        yield "Tuple" => [TupleType::class, "[]"];
        yield "Union" => [UnionType::class, ""];
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Types\ArrayType
     * @covers \DQ5Studios\TypeScript\Generator\Types\IntersectionType
     * @covers \DQ5Studios\TypeScript\Generator\Types\TupleType
     * @covers \DQ5Studios\TypeScript\Generator\Types\UnionType
     * @dataProvider typeList
     * @param class-string<Type> $class
     */
    public function testToString(string $class, string $expected): void
    {
        $type = new $class();
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
        yield "Intersection" => [IntersectionType::class, $contains, "number & string"];
        yield "Tuple" => [TupleType::class, $contains, "[number,string]"];
        yield "Union" => [UnionType::class, $contains, "number | string"];
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
        $type->contains(...$contains);
        $this->assertSame($expected, (string) $type);

        $type = $class::from(...$contains);
        $this->assertInstanceOf(Type::class, $type);
        $this->assertInstanceOf(MultiType::class, $type);
        $this->assertSame($expected, (string) $type);
    }

    public function testBadData(): void
    {
        $type = new ArrayType();
        try {
            $type->contains("Not a valid type");
            $this->fail("Accepted bad data");
        } catch (Exception $e) {
            $this->assertInstanceOf(UnexpectedValueException::class, $e);
        }
    }

    public function testSeperator(): void
    {
        $type = new class extends MultiType{
        };
        $contains = [NumberType::class, new StringType()];
        $type->contains(...$contains);
        $type->setSeperator("??");
        $this->assertSame("number??string", (string) $type);
    }
}
