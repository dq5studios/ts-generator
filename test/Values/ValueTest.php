<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Types\BooleanType;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\LiteralType;
use DQ5Studios\TypeScript\Generator\Types\NoneType;
use DQ5Studios\TypeScript\Generator\Types\NullType;
use DQ5Studios\TypeScript\Generator\Types\NumberType;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Types\UndefinedType;
use Generator;
use PHPUnit\Framework\TestCase;

class ValueTest extends TestCase
{
    public function literalList(): Generator
    {
        yield "Boolean" => [BooleanValue::class, true, "true"];
        yield "Null" => [NullValue::class, null, "null"];
        yield "Number" => [NumberValue::class, 42, "42"];
        yield "String" => [StringValue::class, "skimbleshanks", "\"skimbleshanks\""];
        yield "Undefined" => [UndefinedValue::class, null, "undefined"];
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Values\BooleanValue
     * @covers \DQ5Studios\TypeScript\Generator\Values\NullValue
     * @covers \DQ5Studios\TypeScript\Generator\Values\NumberValue
     * @covers \DQ5Studios\TypeScript\Generator\Values\StringValue
     * @covers \DQ5Studios\TypeScript\Generator\Values\UndefinedValue
     * @dataProvider literalList
     * @param class-string<Value> $class
     */
    public function testToLiteralType(string $class, mixed $initial, string $expected): void
    {
        /** @psalm-suppress UnsafeInstantiation */
        $value = new $class($initial);
        $this->assertInstanceOf(Value::class, $value);
        $this->assertInstanceOf(LiteralType::class, $value);
        $type = $value->asLiteral();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertSame($expected, (string) $type);
    }

    public function valueList(): Generator
    {
        yield "Boolean" => [BooleanValue::class, true, "true"];
        yield "None" => [NoneValue::class, null, ""];
        yield "Null" => [NullValue::class, null, "null"];
        yield "Number" => [NumberValue::class, 42, "42"];
        yield "String" => [StringValue::class, "skimbleshanks", "\"skimbleshanks\""];
        yield "Undefined" => [UndefinedValue::class, null, "undefined"];
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Values\BooleanValue
     * @covers \DQ5Studios\TypeScript\Generator\Values\NoneValue
     * @covers \DQ5Studios\TypeScript\Generator\Values\NullValue
     * @covers \DQ5Studios\TypeScript\Generator\Values\NumberValue
     * @covers \DQ5Studios\TypeScript\Generator\Values\StringValue
     * @covers \DQ5Studios\TypeScript\Generator\Values\UndefinedValue
     * @dataProvider valueList
     * @param class-string<Value> $class
     */
    public function testToString(string $class, mixed $initial, string $expected): void
    {
        /** @psalm-suppress UnsafeInstantiation */
        $value = new $class($initial);
        $this->assertInstanceOf(Value::class, $value);
        $this->assertSame($expected, (string) $value);
    }

    public function typeList(): Generator
    {
        yield "Boolean" => [BooleanValue::class, true, BooleanType::class];
        yield "None" => [NoneValue::class, null, NoneType::class];
        yield "Null" => [NullValue::class, null, NullType::class];
        yield "Number" => [NumberValue::class, 42, NumberType::class];
        yield "String" => [StringValue::class, "skimbleshanks", StringType::class];
        yield "Undefined" => [UndefinedValue::class, null, UndefinedType::class];
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Values\BooleanValue
     * @covers \DQ5Studios\TypeScript\Generator\Values\NoneValue
     * @covers \DQ5Studios\TypeScript\Generator\Values\NullValue
     * @covers \DQ5Studios\TypeScript\Generator\Values\NumberValue
     * @covers \DQ5Studios\TypeScript\Generator\Values\StringValue
     * @covers \DQ5Studios\TypeScript\Generator\Values\UndefinedValue
     * @covers \DQ5Studios\TypeScript\Generator\Values\Value
     * @dataProvider typeList
     * @param class-string<Value> $class
     */
    public function testGetType(string $class, mixed $initial, string $expected): void
    {
        /** @psalm-suppress UnsafeInstantiation */
        $value = new $class($initial);
        $this->assertInstanceOf(Value::class, $value);
        $this->assertSame($expected, $value->getType());
    }

    public function getSetList(): Generator
    {
        yield "Boolean" => [BooleanValue::class, true, false];
        yield "Number" => [NumberValue::class, 42, 76];
        yield "String" => [StringValue::class, "skimbleshanks", "asparagus"];
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Values\BooleanValue
     * @covers \DQ5Studios\TypeScript\Generator\Values\NumberValue
     * @covers \DQ5Studios\TypeScript\Generator\Values\StringValue
     * @dataProvider getSetList
     * @param class-string<BooleanValue>|class-string<NumberValue>|class-string<StringValue> $class
     */
    public function testGetSet(string $class, bool | int | string $initial, bool | int | string $expected): void
    {
        /** @psalm-suppress UnsafeInstantiation */
        $value = new $class($initial);
        $this->assertInstanceOf(Value::class, $value);
        $this->assertInstanceOf($class, $value);
        $value = $value->setValue($expected);
        $this->assertInstanceOf(Value::class, $value);
        $this->assertInstanceOf($class, $value);
        $this->assertSame($expected, $value->getValue());
    }
}
