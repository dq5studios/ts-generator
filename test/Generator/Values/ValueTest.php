<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Values;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Types\ArrayType;
use DQ5Studios\TypeScript\Generator\Types\BooleanType;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\LiteralType;
use DQ5Studios\TypeScript\Generator\Types\NoneType;
use DQ5Studios\TypeScript\Generator\Types\NullType;
use DQ5Studios\TypeScript\Generator\Types\NumberType;
use DQ5Studios\TypeScript\Generator\Types\ObjectType;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Types\UndefinedType;
use DQ5Studios\TypeScript\Generator\Values\ArrayValue;
use DQ5Studios\TypeScript\Generator\Values\BooleanValue;
use DQ5Studios\TypeScript\Generator\Values\NoneValue;
use DQ5Studios\TypeScript\Generator\Values\NullValue;
use DQ5Studios\TypeScript\Generator\Values\NumberValue;
use DQ5Studios\TypeScript\Generator\Values\ObjectValue;
use DQ5Studios\TypeScript\Generator\Values\StringValue;
use DQ5Studios\TypeScript\Generator\Values\UndefinedValue;
use DQ5Studios\TypeScript\Generator\Values\Value;
use Generator;
use PHPUnit\Framework\TestCase;
use stdClass;

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
     * @covers \DQ5Studios\TypeScript\Generator\Printer
     *
     * @dataProvider literalList
     *
     * @param class-string<Value> $class
     */
    public function testToLiteralType(string $class, mixed $initial, string $expected): void
    {
        $value = new $class($initial);
        $this->assertInstanceOf(Value::class, $value);
        $this->assertInstanceOf(LiteralType::class, $value);
        $type = $value->asLiteral();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertSame($expected, Printer::print($type));
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
     * @covers \DQ5Studios\TypeScript\Generator\Values\Value
     *
     * @dataProvider valueList
     *
     * @param class-string<Value> $class
     */
    public function testToString(string $class, mixed $initial, string $expected): void
    {
        $value = new $class($initial);
        $this->assertInstanceOf(Value::class, $value);
        $this->assertSame($expected, Printer::print($value));
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
     *
     * @dataProvider typeList
     *
     * @param class-string<Value> $class
     */
    public function testGetType(string $class, mixed $initial, string $expected): void
    {
        $value = new $class($initial);
        $this->assertInstanceOf(Value::class, $value);
        $this->assertSame($expected, $value->getType());
    }

    public function fromList(): Generator
    {
        yield "Boolean" => [true, BooleanValue::class, BooleanType::class];
        yield "Integer" => [42, NumberValue::class, NumberType::class];
        yield "Double" => [42.5, NumberValue::class, NumberType::class];
        yield "String" => ["skimbleshanks", StringValue::class, StringType::class];
        yield "Null" => [null, NullValue::class, NullType::class];
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Values\Value
     *
     * @dataProvider fromList
     *
     * @param class-string<Value> $class
     * @param class-string<Type>  $type
     */
    public function testFrom(mixed $initial, string $class, string $type): void
    {
        $value = Value::from($initial);
        $this->assertInstanceOf(Value::class, $value);
        $this->assertInstanceOf($class, $value);
        $this->assertSame($type, $value->getType());
        // UndefinedValue has no get
        if (method_exists($value, "getValue")) {
            $this->assertSame($initial, $value->getValue());
        }

        $again = Value::from($value);
        $this->assertSame($again, $value);
    }

    public function fromAdvancedList(): Generator
    {
        yield "Array" => [["mungojerrie", "rumpleteazer"], ArrayValue::class, ArrayType::class];
        yield "Object" => [["rumtumtugger" => "jennyanydots", "mungojerrie" => "rumpleteazer"], ObjectValue::class, ObjectType::class];
        yield "Undefined" => [new stdClass(), UndefinedValue::class, UndefinedType::class];
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Values\Value
     *
     * @dataProvider fromAdvancedList
     *
     * @param class-string<Value> $class
     * @param class-string<Type>  $type
     */
    public function testAdvancedFrom(mixed $initial, string $class, string $type): void
    {
        $value = Value::from($initial);
        $this->assertInstanceOf(Value::class, $value);
        $this->assertInstanceOf($class, $value);
        $this->assertSame($type, $value->getType());

        $again = Value::from($value);
        $this->assertSame($again, $value);
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
     *
     * @dataProvider getSetList
     *
     * @param class-string<BooleanValue>|class-string<NumberValue>|class-string<StringValue> $class
     */
    public function testGetSet(string $class, bool|int|string $initial, bool|int|string $expected): void
    {
        $value = new $class($initial);
        $this->assertInstanceOf(Value::class, $value);
        $this->assertInstanceOf($class, $value);
        $value = $value->setValue($expected);
        $this->assertInstanceOf(Value::class, $value);
        $this->assertInstanceOf($class, $value);
        $this->assertSame($expected, $value->getValue());
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Values\NullValue
     */
    public function testNullValue(): void
    {
        $value = new NullValue();
        $this->assertInstanceOf(Value::class, $value);
        $this->assertNull($value->getValue());
    }
}
