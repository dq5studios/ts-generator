<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Types;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Types\ArrayType;
use DQ5Studios\TypeScript\Generator\Types\IntersectionType;
use DQ5Studios\TypeScript\Generator\Types\NumberType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Types\UnionType;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Types\Type
 * @covers \DQ5Studios\TypeScript\Generator\Printer
 */
class TypeTest extends TestCase
{
    public function testToString(): void
    {
        $type = new class () extends Type {
            protected string $type = "jellicle";
        };
        $this->assertInstanceOf(Type::class, $type);
        $this->assertSame("jellicle", Printer::print($type));
    }

    public function testFromWithString(): void
    {
        $actual = Type::from(NumberType::class);
        $this->assertInstanceOf(Type::class, $actual);
        $this->assertInstanceOf(NumberType::class, $actual);
    }

    public function testFromWithObject(): void
    {
        $actual = Type::from(new NumberType());
        $this->assertInstanceOf(Type::class, $actual);
        $this->assertInstanceOf(NumberType::class, $actual);
    }

    public function testNotAType(): void
    {
        try {
            Type::from("not a type");
            $this->fail("Did not reject invalid type");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }

    public function testFromMulti(): void
    {
        $actual = Type::from("number|string");
        $this->assertInstanceOf(UnionType::class, $actual);
        $this->assertSame("(number | string)", Printer::print($actual));

        $actual = Type::from("number&string");
        $this->assertInstanceOf(IntersectionType::class, $actual);
        $this->assertSame("(number & string)", Printer::print($actual));

        $actual = Type::from("(number|string)[]");
        $this->assertInstanceOf(ArrayType::class, $actual);
        $this->assertSame("(number|string)[]", Printer::print($actual));

        $actual = Type::from("number[]");
        $this->assertInstanceOf(ArrayType::class, $actual);
        $this->assertSame("number[]", Printer::print($actual));
    }

    public function testGetSet(): void
    {
        $actual = $this->getMockForAbstractClass(Type::class);
        $actual->setType("jellicle");
        $this->assertInstanceOf(Type::class, $actual);
        $this->assertSame("jellicle", Printer::print($actual));
        $this->assertSame("jellicle", $actual->getType());
    }
}
