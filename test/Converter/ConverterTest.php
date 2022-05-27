<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Converter;

use DQ5Studios\TypeScript\ClassTestClass;
use DQ5Studios\TypeScript\ClassTestEnum;
use DQ5Studios\TypeScript\ClassTestInterface;
use DQ5Studios\TypeScript\ExampleClass;
use DQ5Studios\TypeScript\Generator\File;
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
}
