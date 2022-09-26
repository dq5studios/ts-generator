<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Types\Traits;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Tokens\ObjectPropertyToken;
use DQ5Studios\TypeScript\Generator\Types\NumberType;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasFunctionSignature;
use DQ5Studios\TypeScript\Generator\Types\Type;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Types\Traits\HasFunctionSignature
 */
class HasFunctionSignatureTest extends TestCase
{
    public function testMethods(): void
    {
        $mock = (new class () extends Type {
            use HasFunctionSignature;
            protected array $properties = [];

            public function addProperty(string|NameToken $name, string|Type $type): ObjectPropertyToken
            {
                $member = ObjectPropertyToken::from($name, $type);

                return $this->properties[Printer::print($member->getName())] = $member;
            }
        });

        $actual_mock = clone $mock;
        $this->assertSame("(): void", Printer::print($actual_mock->addCallableSignature()));

        $actual_mock = clone $mock;
        $this->assertSame("(): string", Printer::print($actual_mock->addCallableSignature(StringType::class)));

        $actual_mock = clone $mock;
        $this->assertSame("(arg_0: string): number", Printer::print($actual_mock->addCallableSignature(StringType::class, NumberType::class)));

        $actual_mock = clone $mock;
        $this->assertSame("new (): void", Printer::print($actual_mock->addConstructorSignature()));

        $actual_mock = clone $mock;
        $this->assertSame("new (): string", Printer::print($actual_mock->addConstructorSignature(StringType::class)));

        $actual_mock = clone $mock;
        $this->assertSame("new (arg_0: string): number", Printer::print($actual_mock->addConstructorSignature(StringType::class, NumberType::class)));

        $actual_mock = clone $mock;
        $this->assertSame("skimbleshanks(): void", Printer::print($actual_mock->addMethodSignature("skimbleshanks")));

        $actual_mock = clone $mock;
        $this->assertSame("skimbleshanks(): string", Printer::print($actual_mock->addMethodSignature("skimbleshanks", StringType::class)));

        $actual_mock = clone $mock;
        $this->assertSame("skimbleshanks(arg_0: string): number", Printer::print($actual_mock->addMethodSignature("skimbleshanks", StringType::class, NumberType::class)));
    }
}
