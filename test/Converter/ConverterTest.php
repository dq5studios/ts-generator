<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Converter;

use DQ5Studios\TypeScript\Converter\Convert;
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

        $output = (string) $interface;

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

        $output = (string) $class;

        $this->assertSame($expected, $output);
    }

    public function testEnumConversion(): void
    {
        $expected = <<<HEREDOC
enum ClassTestEnum {
    /** Unknown */
    unknown = 0,
    /** Pending */
    pending = 1,
    /** Approved */
    approved = 2,
    /** Removed */
    removed = 3,
}
HEREDOC;

        $enum = Convert::fromPHP(ClassTestEnum::class);

        $output = (string) $enum;

        $this->assertSame($expected, $output);
    }

    /**
     * @requires PHP >= 8.1
     */
    public function testNativeEnumConversion(): void
    {
        $expected = <<<HEREDOC
enum ClassTestNativeEnum {
    /** @var int Unknown */
    unknown,
    /** @var int Pending */
    pending,
    /** @var int Approved */
    approved,
    /** @var int Removed */
    removed,
}
HEREDOC;

        $enum = Convert::fromPHP(ClassTestNativeEnum::class);

        $output = (string) $enum;

        $this->assertSame($expected, $output);
    }

    /**
     * @requires PHP >= 8.1
     */
    public function testNativeBackedEnumConversion(): void
    {
        $expected = <<<HEREDOC
enum ClassTestNativeBackedEnum {
    /** @var int Unknown */
    unknown = 0,
    /** @var int Pending */
    pending = 1,
    /** @var int Approved */
    approved = 2,
    /** @var int Removed */
    removed = 3,
}
HEREDOC;

        $enum = Convert::fromPHP(ClassTestNativeBackedEnum::class);

        $output = (string) $enum;

        $this->assertSame($expected, $output);
    }
}
