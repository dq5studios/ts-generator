<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Converter;

use DQ5Studios\TypeScript\Converter\Convert;
use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Tests\ClassTestClass;
use DQ5Studios\TypeScript\Tests\ClassTestEnum;
use DQ5Studios\TypeScript\Tests\ClassTestExtends;
use DQ5Studios\TypeScript\Tests\ClassTestInterface;
use DQ5Studios\TypeScript\Tests\ClassTestNativeBackedEnum;
use DQ5Studios\TypeScript\Tests\ClassTestNativeEnum;
use DQ5Studios\TypeScript\Tests\InterfaceTestInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\StringSourceLocator;
use Throwable;

/**
 * @covers \DQ5Studios\TypeScript\Converter\Convert
 */
class ConverterTest extends TestCase
{
    public function testFailure(): void
    {
        try {
            Convert::fromPHP("invalid");
        } catch (Throwable $th) {
            $this->assertInstanceOf(InvalidArgumentException::class, $th);
        }
    }

    public function testInterfaceConversionFromFile(): void
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

    public function testInterfaceConversionFromObject(): void
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

        $interface = Convert::fromPHP(new ClassTestInterface());

        $output = Printer::print($interface);

        $this->assertSame($expected, $output);
    }

    public function testInterfaceConversionFromString(): void
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

        $code = file_get_contents(__DIR__ . "/../ClassTestInterface.php");
        $ast_locator = (new BetterReflection())->astLocator();
        $reflector = new DefaultReflector(new StringSourceLocator($code, $ast_locator));
        $reflection_class = $reflector->reflectAllClasses()[0];
        $interface = Convert::fromPHP($reflection_class);

        $output = Printer::print($interface);

        $this->assertSame($expected, $output);
    }

    public function testInterfaceConversionFromInterface(): void
    {
        $expected = <<<HEREDOC
            interface InterfaceTestInterface {
            }
            HEREDOC;

        $class = Convert::fromPHP(InterfaceTestInterface::class);

        $output = Printer::print($class);

        $this->assertSame($expected, $output);
    }

    public function testClassExtends(): void
    {
        $expected = <<<HEREDOC
            class ClassTestExtends extends ClassTestClass {
                public key: string;
                /** All the names */
                public list: (string | number)[] = ["name", "scram"];
                /** Special value */
                protected value: number = 42;
                public ex: (null | PhpDocExtractor);
                public readonly readonly_prop: string;
            }
            HEREDOC;

        $class = Convert::fromPHP(ClassTestExtends::class);

        $output = Printer::print($class);

        $this->assertSame($expected, $output);
    }

    public function testClassConversionFromFile(): void
    {
        $expected = <<<HEREDOC
            export class ClassTestClass {
                public key: string;
                /** All the names */
                public list: (string | number)[];
                /** Special value */
                protected value: number;
                public ex: (null | PhpDocExtractor);
                private surprise: { [index: string]: unknown };
            }
            HEREDOC;

        $class = Convert::fromPHP(ClassTestClass::class);

        $output = Printer::print($class);

        $this->assertSame($expected, $output);
    }

    public function testEnumConversionFromFile(): void
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
    public function testNativeEnumConversionFromFile(): void
    {
        $expected = <<<HEREDOC
            export enum ClassTestNativeEnum {
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
    public function testNativeEnumConversionFromString(): void
    {
        $expected = <<<HEREDOC
            export enum ClassTestNativeEnum {
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

        $code = file_get_contents(__DIR__ . "/../ClassTestNativeEnum.php");
        $ast_locator = (new BetterReflection())->astLocator();
        $reflector = new DefaultReflector(new StringSourceLocator($code, $ast_locator));
        $reflection_class = $reflector->reflectAllClasses()[0];
        $enum = Convert::fromPHP($reflection_class);

        $output = Printer::print($enum);

        $this->assertSame($expected, $output);
    }

    /**
     * @requires PHP >= 8.1
     */
    public function testNativeBackedEnumConversionFromFile(): void
    {
        $expected = <<<HEREDOC
            export enum ClassTestNativeBackedEnum {
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

    /**
     * @requires PHP >= 8.1
     */
    public function testNativeBackedEnumConversionFromString(): void
    {
        $expected = <<<HEREDOC
            export enum ClassTestNativeBackedEnum {
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

        $code = file_get_contents(__DIR__ . "/../ClassTestNativeBackedEnum.php");
        $ast_locator = (new BetterReflection())->astLocator();
        $reflector = new DefaultReflector(new StringSourceLocator($code, $ast_locator));
        $reflection_class = $reflector->reflectAllClasses()[0];
        $enum = Convert::fromPHP($reflection_class);

        $output = Printer::print($enum);

        $this->assertSame($expected, $output);
    }
}
