<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Tokens\FunctionSignatureToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\ArrayType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Tokens\FunctionSignatureToken
 */
class FunctionSignatureTokenTest extends TestCase
{
    public function testOf(): void
    {
        $actual = FunctionSignatureToken::of("fn", "string", "string");
        $this->assertSame("(arg_0: string, arg_1: string)", Printer::print($actual));
        $actual = FunctionSignatureToken::of(NameToken::from("fn"), "string", "string");
        $this->assertSame("(arg_0: string, arg_1: string)", Printer::print($actual));
    }

    public function testSetConstructor(): void
    {
        $actual = FunctionSignatureToken::of("fn", "string", "string");
        $actual->hasConstructor(true);
        $this->assertSame("new (arg_0: string, arg_1: string)", Printer::print($actual));
        $this->assertTrue($actual->isConstructor());
    }

    public function testSetCallable(): void
    {
        $actual = FunctionSignatureToken::of("fn", "string", "string");
        $actual->hasCallable(true);
        $this->assertSame("(arg_0: string, arg_1: string)", Printer::print($actual));
        $this->assertTrue($actual->isCallable());
    }

    public function testSetMethod(): void
    {
        $actual = FunctionSignatureToken::of("fn", "string", "string");
        $actual->hasMethod(true);
        $this->assertSame("fn(arg_0: string, arg_1: string)", Printer::print($actual));
        $this->assertTrue($actual->isMethod());
    }

    public function testAdjustParameters(): void
    {
        $actual = new FunctionSignatureToken("fn");
        $actual->addParameter(Type::STRING, "param1");
        $array = ArrayType::of(Type::NUMBER);
        $actual->addParameter($array, "...param2");
        $this->assertSame("(param1: string, ...param2: number[])", Printer::print($actual));
        $parameters = $actual->getParameters();
        $this->assertCount(2, $parameters);
        $actual = new FunctionSignatureToken("fn");
        $actual->setParameters([Type::STRING, $array]);
        $this->assertSame("(arg_0: string, arg_1: number[])", Printer::print($actual));
        $actual = new FunctionSignatureToken("fn");
        $array = ArrayType::of(Type::NUMBER);
        $actual->addParameter($array, "param?");
        $this->assertSame("(param?: number[])", Printer::print($actual));
    }
}
