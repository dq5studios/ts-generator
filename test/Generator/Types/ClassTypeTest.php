<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Types;

use DQ5Studios\TypeScript\Generator\Tokens\ClassPropertyToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\ClassType;
use DQ5Studios\TypeScript\Generator\Types\NumberType;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Types\UnionType;
use DQ5Studios\TypeScript\Generator\Values\NoneValue;
use DQ5Studios\TypeScript\Generator\Values\StringValue;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Types\ClassType
 */
class ClassTypeTest extends TestCase
{
    /**
     * @covers \DQ5Studios\TypeScript\Generator\Types\ContainerType
     */
    public function testCreation(): void
    {
        try {
            new ClassType();
            $this->fail("Failed requiring name");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }

        $actual = new ClassType("jellicle");
        $this->assertSame("jellicle", (string) $actual->getName());

        $actual = new ClassType(new NameToken("jellicle"));
        $this->assertSame("jellicle", (string) $actual->getName());
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Types\ContainerType
     */
    public function testSetGetName(): void
    {
        $actual = new ClassType("jellicle");
        $actual->setName("pollicle");
        $this->assertSame("pollicle", (string) $actual->getName());

        $actual = new ClassType(new NameToken("jellicle"));
        $actual->setName(NameToken::from("pollicle"));
        $this->assertSame("pollicle", (string) $actual->getName());
    }

    public function testMemberActions(): void
    {
        $actual = new ClassType("jellicle");
        $actual->addProperty("skimbleshanks", StringType::class, "the railway cat");
        $actual->addProperty(NameToken::from("grizabella"), new NumberType(), 16);
        $members = $actual->getProperties();
        $this->assertCount(2, $members);
        $this->assertContainsOnlyInstancesOf(ClassPropertyToken::class, $members);

        $actual = new ClassType("jellicle");
        $actual->setProperties($members);
        $members = $actual->getProperties();
        $this->assertCount(2, $members);
        $this->assertContainsOnlyInstancesOf(ClassPropertyToken::class, $members);
    }

    public function testMemberActionFailures(): void
    {
        $actual = new ClassType("jellicle");
        try {
            $actual->setProperties(["macavity", "ginger"]);
            $this->fail("Failed to validate property");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }

    public function testToString(): void
    {
        $actual = new ClassType("jellicle");
        $actual->hasAmbient(true);
        $actual->addProperty("skimbleshanks", NumberType::class, 19);
        $actual->addProperty("grizabella", new StringType(), "glamour cat");
        $actual->addProperty(
            "growltiger",
            UnionType::of(
                (new StringValue("mungojerrie"))->asLiteral(),
                (new StringValue("rumpelteazer"))->asLiteral()
            ),
            new NoneValue()
        );

        $expected = <<<'ENUM'
declare class jellicle {
    skimbleshanks: number = 19;
    grizabella: string = "glamour cat";
    growltiger: ("mungojerrie" | "rumpelteazer");
}
ENUM;
        $this->assertSame($expected, (string) $actual);

        $actual = new ClassType("jellicle");
        $actual->addProperty("grizabella", StringType::class, "glamour cat");
        $actual->addProperty("mungojerrie", NumberType::class, 21);
        $actual->addProperty("skimbleshanks", UnionType::of(StringType::class, NumberType::class), new NoneValue());
        $actual->addComment("jellicles can\n\njellicles do");

        $expected = <<<'ENUM'
/**
 * jellicles can
 *
 * jellicles do
 */
class jellicle {
    grizabella: string = "glamour cat";
    mungojerrie: number = 21;
    skimbleshanks: (string | number);
}
ENUM;
        $this->assertSame($expected, (string) $actual);

        $actual = new ClassType("jellicle");
        $subtype = new ClassType("cat");
        $subtype->addProperty("mungojerrie", NumberType::class, 18);
        $actual->addProperty("rumpelteazer", $subtype, new NoneValue());
        $actual->hasExport(true);
        $extend = new ClassType("jellylorum");
        $actual->addExtend($extend);

        $expected = <<<'ENUM'
export class jellicle extends jellylorum {
    rumpelteazer: cat;
}
ENUM;
        $this->assertSame($expected, (string) $actual);
    }

    public function testExport(): void
    {
        $actual = new ClassType("jellicle");
        $actual->hasExport(true);
        $this->assertTrue($actual->isExport());
        $actual->hasExport(false);
        $this->assertFalse($actual->isExport());
    }

    public function testAmbient(): void
    {
        $actual = new ClassType("jellicle");
        $actual->hasAmbient(true);
        $this->assertTrue($actual->isAmbient());
        $actual->hasAmbient(false);
        $this->assertFalse($actual->isAmbient());
    }

    public function testExtend(): void
    {
        $actual = new ClassType("jellicle");
        $first = new ClassType("rumpelteazer");
        $also = new ClassType("jellylorum");
        $actual->addExtend($first);
        $actual->addExtend($also);
        $extends = $actual->getExtend();
        $this->assertContainsOnlyInstancesOf(ClassType::class, $extends);
        $this->assertContains($first, $extends);
        $this->assertContains($also, $extends);

        $actual = new ClassType("jellicle");
        $actual->setExtend([$first, $also]);
        $extends = $actual->getExtend();
        $this->assertContainsOnlyInstancesOf(ClassType::class, $extends);
        $this->assertContains($first, $extends);
        $this->assertContains($also, $extends);
    }

    public function testAddSignatures(): void
    {
        $actual = new ClassType("jellicle");
        $actual->addIndexSignature(Type::STRING, Type::NUMBER);
        $actual->addIndexSignature(Type::NUMBER, Type::STRING);
        $actual->addProperty("skimbleshanks", "string", "the railway cat");

        $expected = <<<'ENUM'
class jellicle {
    [index: string]: number;
    [index: number]: string;
    skimbleshanks: string = "the railway cat";
}
ENUM;
        $this->assertSame($expected, (string) $actual);
    }
}
