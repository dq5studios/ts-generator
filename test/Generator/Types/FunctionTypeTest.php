<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Types;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Tokens\FunctionParameterToken;
use DQ5Studios\TypeScript\Generator\Types\FunctionType;
use DQ5Studios\TypeScript\Generator\Types\NumberType;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use DQ5Studios\TypeScript\Generator\Types\UnionType;
use DQ5Studios\TypeScript\Generator\Types\VoidType;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Types\FunctionType
 * @covers \DQ5Studios\TypeScript\Generator\Printer
 */
class FunctionTypeTest extends TestCase
{
    public function testFunctionVariations(): void
    {
        $function = new FunctionType();
        $function->setReturn(new VoidType());
        $this->assertSame("() => void", Printer::print($function), "Void return as object");

        $function = new FunctionType();
        $function->setReturn(VoidType::class);
        $this->assertInstanceOf(VoidType::class, $function->getReturn());
        $this->assertSame("() => void", Printer::print($function), "Void return as class-string");

        $function = new FunctionType();
        $function->setReturn(UnionType::of(NumberType::class, StringType::class));
        $this->assertSame("() => (number | string)", Printer::print($function), "Union return as class-string");

        $function = new FunctionType();
        $function->setParameters([new NumberType()]);
        $this->assertSame("(arg_0: number) => void", Printer::print($function), "Number parameter as object");

        $function = new FunctionType();
        $function->setParameters([NumberType::class]);
        $this->assertSame("(arg_0: number) => void", Printer::print($function), "Number parameter as class-string");

        $function = new FunctionType();
        $function->setParameters([NumberType::class, StringType::class]);
        $parameters = $function->getParameters();
        $this->assertContainsOnlyInstancesOf(FunctionParameterToken::class, $parameters);
        $this->assertCount(2, $parameters);
        $signature = "(arg_0: number, arg_1: string) => void";
        $this->assertSame($signature, Printer::print($function), "Multiple parameters as class-string");

        $function = new FunctionType();
        try {
            $function->setReturn("Not a valid type");
            $this->fail("Accepted bad data");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }

    public function testAddParameter(): void
    {
        $function = new FunctionType();
        $function->addParameter(new StringType(), "skimbleshanks");
        $this->assertSame("(skimbleshanks: string) => void", Printer::print($function));
        $param = $function->addParameter(new NumberType());
        $this->assertSame("(skimbleshanks: string, arg_1: number) => void", Printer::print($function));
        $param->hasOptional(true);
        $param->setName("rumtumtugger");
        $this->assertSame("(skimbleshanks: string, rumtumtugger?: number) => void", Printer::print($function));
    }
}
