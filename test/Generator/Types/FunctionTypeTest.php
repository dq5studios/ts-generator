<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use DQ5Studios\TypeScript\Generator\Tokens\FunctionParameterToken;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Types\FunctionType
 */
class FunctionTypeTest extends TestCase
{
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
        $function->setReturn(UnionType::of(NumberType::class, StringType::class));
        $this->assertSame("() => (number | string)", (string) $function, "Union return as class-string");

        $function = new FunctionType();
        $function->setParameters([new NumberType()]);
        $this->assertSame("(arg_0: number) => void", (string) $function, "Number parameter as object");

        $function = new FunctionType();
        $function->setParameters([NumberType::class]);
        $this->assertSame("(arg_0: number) => void", (string) $function, "Number parameter as class-string");

        $function = new FunctionType();
        $function->setParameters([NumberType::class, StringType::class]);
        $parameters = $function->getParameters();
        $this->assertContainsOnlyInstancesOf(FunctionParameterToken::class, $parameters);
        $this->assertCount(2, $parameters);
        $signature = "(arg_0: number, arg_1: string) => void";
        $this->assertSame($signature, (string) $function, "Multiple parameters as class-string");

        $function = new FunctionType();
        try {
            /** @psalm-suppress ArgumentTypeCoercion */
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
        $this->assertSame("(skimbleshanks: string) => void", (string) $function);
        $param = $function->addParameter(new NumberType());
        $this->assertSame("(skimbleshanks: string, arg_1: number) => void", (string) $function);
        $param->setOptional(true);
        $param->setName("rumtumtugger");
        $this->assertSame("(skimbleshanks: string, rumtumtugger?: number) => void", (string) $function);
    }
}
