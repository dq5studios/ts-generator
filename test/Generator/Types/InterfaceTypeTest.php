<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Types;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Tokens\InterfacePropertyToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\InterfaceType;
use DQ5Studios\TypeScript\Generator\Types\NumberType;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Types\UnionType;
use DQ5Studios\TypeScript\Generator\Values\StringValue;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Types\InterfaceType
 * @covers \DQ5Studios\TypeScript\Generator\Printer
 */
class InterfaceTypeTest extends TestCase
{
    /**
     * @covers \DQ5Studios\TypeScript\Generator\Types\ContainerType
     */
    public function testCreation(): void
    {
        try {
            new InterfaceType();
            $this->fail("Failed requiring name");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }

        $actual = new InterfaceType("jellicle");
        $this->assertSame("jellicle", Printer::print($actual->getName()));

        $actual = new InterfaceType(new NameToken("jellicle"));
        $this->assertSame("jellicle", Printer::print($actual->getName()));
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Types\ContainerType
     */
    public function testSetGetName(): void
    {
        $actual = new InterfaceType("jellicle");
        $actual->setName("pollicle");
        $this->assertSame("pollicle", Printer::print($actual->getName()));

        $actual = new InterfaceType(new NameToken("jellicle"));
        $actual->setName(NameToken::from("pollicle"));
        $this->assertSame("pollicle", Printer::print($actual->getName()));
    }

    public function testMemberActions(): void
    {
        $actual = new InterfaceType("jellicle");
        $actual->addProperty("skimbleshanks", StringType::class);
        $actual->addProperty(NameToken::from("grizabella"), new NumberType());
        $members = $actual->getProperties();
        $this->assertCount(2, $members);
        $this->assertContainsOnlyInstancesOf(InterfacePropertyToken::class, $members);

        $actual = new InterfaceType("jellicle");
        $actual->setProperties($members);
        $members = $actual->getProperties();
        $this->assertCount(2, $members);
        $this->assertContainsOnlyInstancesOf(InterfacePropertyToken::class, $members);
    }

    public function testMemberActionFailures(): void
    {
        $actual = new InterfaceType("jellicle");
        try {
            $actual->setProperties(["macavity", "ginger"]);
            $this->fail("Failed to validate property");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }

    public function testToString(): void
    {
        $actual = new InterfaceType("jellicle");
        $actual->hasAmbient(true);
        $actual->addProperty("skimbleshanks", NumberType::class);
        $actual->addProperty("grizabella", new StringType());
        $actual->addProperty(
            "growltiger",
            UnionType::of(
                (new StringValue("mungojerrie"))->asLiteral(),
                (new StringValue("rumpelteazer"))->asLiteral()
            )
        );

        $expected = <<<'INTERFACE'
            declare interface jellicle {
                skimbleshanks: number;
                grizabella: string;
                growltiger: ("mungojerrie" | "rumpelteazer");
            }
            INTERFACE;
        $this->assertSame($expected, Printer::print($actual));

        $actual = new InterfaceType("jellicle");
        $actual->addProperty("grizabella", StringType::class);
        $actual->addProperty("mungojerrie", NumberType::class);
        $actual->addProperty("skimbleshanks", UnionType::of(StringType::class, NumberType::class));
        $actual->addComment("jellicles can\n\njellicles do");

        $expected = <<<'INTERFACE'
            /**
             * jellicles can
             *
             * jellicles do
             */
            interface jellicle {
                grizabella: string;
                mungojerrie: number;
                skimbleshanks: (string | number);
            }
            INTERFACE;
        $this->assertSame($expected, Printer::print($actual));

        $actual = new InterfaceType("jellicle");
        $subtype = new InterfaceType("cat");
        $subtype->addProperty("mungojerrie", NumberType::class);
        $actual->addProperty("rumpelteazer", $subtype);
        $actual->hasExport(true);
        $extend = new InterfaceType("jellylorum");
        $actual->addExtend($extend);

        $expected = <<<'INTERFACE'
            export interface jellicle extends jellylorum {
                rumpelteazer: cat;
            }
            INTERFACE;
        $this->assertSame($expected, Printer::print($actual));
    }

    public function testExport(): void
    {
        $actual = new InterfaceType("jellicle");
        $actual->hasExport(true);
        $this->assertTrue($actual->isExport());
        $actual->hasExport(false);
        $this->assertFalse($actual->isExport());
    }

    public function testAmbient(): void
    {
        $actual = new InterfaceType("jellicle");
        $actual->hasAmbient(true);
        $this->assertTrue($actual->isAmbient());
        $actual->hasAmbient(false);
        $this->assertFalse($actual->isAmbient());
    }

    public function testExtend(): void
    {
        $actual = new InterfaceType("jellicle");
        $first = new InterfaceType("rumpelteazer");
        $also = new InterfaceType("jellylorum");
        $actual->addExtend($first);
        $actual->addExtend($also);
        $extends = $actual->getExtend();
        $this->assertContainsOnlyInstancesOf(InterfaceType::class, $extends);
        $this->assertContains($first, $extends);
        $this->assertContains($also, $extends);

        $actual = new InterfaceType("jellicle");
        $actual->setExtend([$first, $also]);
        $extends = $actual->getExtend();
        $this->assertContainsOnlyInstancesOf(InterfaceType::class, $extends);
        $this->assertContains($first, $extends);
        $this->assertContains($also, $extends);
    }

    public function testAddSignatures(): void
    {
        $actual = new InterfaceType("jellicle");
        $actual->addIndexSignature(Type::STRING, Type::NUMBER);
        $actual->addIndexSignature(Type::NUMBER, Type::STRING);
        $actual->addCallableSignature(Type::STRING, Type::STRING, Type::VOID);
        $actual->addConstructorSignature(Type::NUMBER, Type::VOID);
        $actual->addProperty("skimbleshanks", "string");

        $expected = <<<'INTERFACE'
            interface jellicle {
                [index: string]: number;
                [index: number]: string;
                (arg_0: string, arg_1: string): void;
                new (arg_0: number): void;
                skimbleshanks: string;
            }
            INTERFACE;
        $this->assertSame($expected, Printer::print($actual));
    }
}
