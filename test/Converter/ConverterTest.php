<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Converter;

use DQ5Studios\TypeScript\Converter\Convert;
use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Tests\ClassTestClass;
use DQ5Studios\TypeScript\Tests\ClassTestEnum;
use DQ5Studios\TypeScript\Tests\ClassTestInterface;
use DQ5Studios\TypeScript\Tests\ClassTestNativeBackedEnum;
use DQ5Studios\TypeScript\Tests\ClassTestNativeEnum;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Converter\Convert
 */
class ConverterTest extends TestCase
{
    public function testInterfaceConversion(): void
    {
        $expected = <<<HEREDOC
interface ClassTestInterface {
    key: string;
    /** All the names */
    list: (string | number)[];
    /** Special value */
    value: number;
    ex: (null | PhpDocExtractor);
    surprise: { [index: string]: unknown };
}
HEREDOC;

        $interface = Convert::fromPHP(ClassTestInterface::class);

        $output = Printer::print($interface);

        $this->assertSame($expected, $output);
    }

    public function testClassConversion(): void
    {
        $expected = <<<HEREDOC
class ClassTestClass {
    public key: string = "name";
    /** All the names */
    public list: (string | number)[] = ["name", "scram"];
    /** Special value */
    protected value: number = 42;
    public ex: (null | PhpDocExtractor);
    private surprise: { [index: string]: unknown } = { "a1": 1, "b2": 2, "c3": 3 };
}
HEREDOC;

        $class = Convert::fromPHP(ClassTestClass::class);

        $output = Printer::print($class);

        $this->assertSame($expected, $output);
    }

    public function testEnumConversion(): void
    {
        $expected = <<<HEREDOC
enum ClassTestEnum {
    /** skimbleshanks */
    skimbleshanks = 0,
    /** mungojerrie */
    mungojerrie = 1,
    /** rumpelteazer */
    rumpelteazer = 2,
    /** macavity */
    macavity = 3,
}
HEREDOC;

        $enum = Convert::fromPHP(ClassTestEnum::class);

        $output = Printer::print($enum);

        $this->assertSame($expected, $output);
    }

    /**
     * @requires PHP >= 8.1
     */
    public function testNativeEnumConversion(): void
    {
        $expected = <<<HEREDOC
enum ClassTestNativeEnum {
    /** @var int skimbleshanks */
    skimbleshanks,
    /** @var int mungojerrie */
    mungojerrie,
    /** @var int rumpelteazer */
    rumpelteazer,
    /** @var int macavity */
    macavity,
}
HEREDOC;

        $enum = Convert::fromPHP(ClassTestNativeEnum::class);

        $output = Printer::print($enum);

        $this->assertSame($expected, $output);
    }

    /**
     * @requires PHP >= 8.1
     */
    public function testNativeBackedEnumConversion(): void
    {
        $expected = <<<HEREDOC
enum ClassTestNativeBackedEnum {
    /** @var int skimbleshanks */
    skimbleshanks = 1,
    /** @var int mungojerrie */
    mungojerrie = 2,
    /** @var int rumpelteazer */
    rumpelteazer = 3,
    /** @var int macavity */
    macavity = 4,
}
HEREDOC;

        $enum = Convert::fromPHP(ClassTestNativeBackedEnum::class);

        $output = Printer::print($enum);

        $this->assertSame($expected, $output);
    }
}
