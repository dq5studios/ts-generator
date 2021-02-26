<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use DQ5Studios\TypeScript\Generator\Tokens\FunctionParameterToken;
use Exception;
use Generator;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

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
     * @dataProvider typeList
     * @param class-string<Type> $class
     */
    public function testToString(string $class, string $expected): void
    {
        $type = new $class();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertSame($expected, (string) $type);
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Types\FunctionType
     */
    public function testFunctionVariations(): void
    {
        $function = new FunctionType();
        $function->setReturn(new VoidType());
        $this->assertSame("() => void", (string) $function, "Void return as object");

        $function = new FunctionType();
        $function->setReturn(VoidType::class);
        $this->assertInstanceOf(VoidType::class, $function->getReturn());
        $this->assertSame("() => void", (string) $function, "Void return as class-string");

        $function = new FunctionType();
        $function->setReturn(UnionType::from(NumberType::class, StringType::class));
        $this->assertSame("() => number | string", (string) $function, "Union return as class-string");

        $function = new FunctionType();
        $function->setParameters(new NumberType());
        $this->assertSame("(arg_0: number) => void", (string) $function, "Number parameter as object");

        $function = new FunctionType();
        $function->setParameters(NumberType::class);
        $this->assertSame("(arg_0: number) => void", (string) $function, "Number parameter as class-string");

        $function = new FunctionType();
        $function->setParameters(NumberType::class, StringType::class);
        $parameters = $function->getParameters();
        $this->assertContainsOnlyInstancesOf(FunctionParameterToken::class, $parameters);
        $this->assertCount(2, $parameters);
        $signature = "(arg_0: number, arg_1: string) => void";
        $this->assertSame($signature, (string) $function, "Multiple parameters as class-string");
        $this->assertSame($signature, $function->getSignature());

        $function = new FunctionType();
        $signature = "(arg_0: number, arg_1: string) => void";
        $function->setSignature($signature);
        $this->assertSame($signature, (string) $function, "Multiple parameters as class-string");
        $this->assertSame($signature, $function->getSignature());

        $function = new FunctionType();
        try {
            $function->setReturn("Not a valid type");
            $this->fail("Accepted bad data");
        } catch (Exception $e) {
            $this->assertInstanceOf(UnexpectedValueException::class, $e);
        }
    }
}
