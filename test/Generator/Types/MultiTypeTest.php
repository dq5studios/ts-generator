<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Types;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Types\ArrayType;
use DQ5Studios\TypeScript\Generator\Types\IntersectionType;
use DQ5Studios\TypeScript\Generator\Types\MultiType;
use DQ5Studios\TypeScript\Generator\Types\NumberType;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use DQ5Studios\TypeScript\Generator\Types\TupleType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Types\UnionType;
use Exception;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ArrayType::class)]
#[CoversClass(IntersectionType::class)]
#[CoversClass(MultiType::class)]
#[CoversClass(TupleType::class)]
#[CoversClass(Type::class)]
#[CoversClass(Type::class)]
#[CoversClass(UnionType::class)]
class MultiTypeTest extends TestCase
{
    public static function typeList(): Generator
    {
        yield "Array" => [ArrayType::class, Type::ARRAY, "[]"];
        yield "Intersection" => [IntersectionType::class, Type::INTERSECTION, ""];
        yield "Tuple" => [TupleType::class, Type::TUPLE, "[]"];
        yield "Union" => [UnionType::class, Type::UNION, ""];
    }

    /**
     * @param class-string<Type> $class
     */
    #[DataProvider(typeList::class)]
    public function testToString(string $class, string $as_string, string $expected): void
    {
        $type = new $class();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertInstanceOf(MultiType::class, $type);
        $this->assertSame($expected, Printer::print($type));
        $type = Type::from($as_string);
        $this->assertInstanceOf(Type::class, $type);
        $this->assertInstanceOf(MultiType::class, $type);
        $this->assertSame($expected, Printer::print($type));
    }

    public static function singleTypeList(): Generator
    {
        $contains = [StringType::class];
        yield "Array" => [ArrayType::class, $contains, "string[]"];
        yield "Intersection" => [IntersectionType::class, $contains, "string"];
        yield "Tuple" => [TupleType::class, $contains, "[string]"];
        yield "Union" => [UnionType::class, $contains, "string"];
    }

    /**
     * @param class-string<Type>       $class
     * @param list<class-string<Type>> $contains
     */
    #[DataProvider(singleTypeList::class)]
    public function testSingleTypeToString(string $class, array $contains, string $expected): void
    {
        $type = new $class();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertInstanceOf(MultiType::class, $type);
        $type->contains(...$contains);
        $this->assertSame($expected, Printer::print($type));
    }

    public static function multiTypeList(): Generator
    {
        $contains = [NumberType::class, new StringType()];
        yield "Array" => [ArrayType::class, $contains, "(number|string)[]"];
        yield "Intersection" => [IntersectionType::class, $contains, "(number & string)"];
        yield "Tuple" => [TupleType::class, $contains, "[number,string]"];
        yield "Union" => [UnionType::class, $contains, "(number | string)"];
    }

    /**
     * @param class-string<Type>       $class
     * @param list<class-string<Type>> $contains
     */
    #[DataProvider(multiTypeList::class)]
    public function testMultiTypeToString(string $class, array $contains, string $expected): void
    {
        $type = new $class();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertInstanceOf(MultiType::class, $type);
        $this->assertTrue($type instanceof $class);
        $type->contains(...$contains);
        $this->assertSame($expected, Printer::print($type));

        $type = $class::of(...$contains);
        $this->assertInstanceOf(Type::class, $type);
        $this->assertInstanceOf(MultiType::class, $type);
        $this->assertInstanceOf($class, $type);
        $this->assertSame($expected, Printer::print($type));
    }

    public function testBadData(): void
    {
        $type = new ArrayType();
        try {
            $type->contains("Not a valid type");
            $this->fail("Accepted bad data");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }

    public function testSeperator(): void
    {
        $type = new class () extends MultiType {
        };
        $contains = [NumberType::class, new StringType()];
        $type->contains(...$contains);
        $type->setSeperator("??");
        $this->assertSame("(number??string)", Printer::print($type));
    }

    public function testFrom(): void
    {
        $actual = Type::from("number|string");
        $this->assertInstanceOf(UnionType::class, $actual);
        $this->assertSame("(number | string)", Printer::print($actual));

        $actual = Type::from("number&string");
        $this->assertInstanceOf(IntersectionType::class, $actual);
        $this->assertSame("(number & string)", Printer::print($actual));
    }
}
