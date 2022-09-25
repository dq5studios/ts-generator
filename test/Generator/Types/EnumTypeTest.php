<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Types;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Tokens\EnumMemberToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\EnumType;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Types\EnumType
 */
class EnumTypeTest extends TestCase
{
    /**
     * @covers \DQ5Studios\TypeScript\Generator\Types\ContainerType
     */
    public function testCreation(): void
    {
        try {
            new EnumType();
            $this->fail("Failed requiring name");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }

        $actual = new EnumType("jellicle");
        $this->assertSame("jellicle", Printer::print($actual->getName()));

        $actual = new EnumType(new NameToken("jellicle"));
        $this->assertSame("jellicle", Printer::print($actual->getName()));
    }

    /**
     * @covers \DQ5Studios\TypeScript\Generator\Types\ContainerType
     */
    public function testSetGetName(): void
    {
        $actual = new EnumType("jellicle");
        $actual->setName("pollicle");
        $this->assertSame("pollicle", Printer::print($actual->getName()));

        $actual = new EnumType(new NameToken("jellicle"));
        $actual->setName(NameToken::from("pollicle"));
        $this->assertSame("pollicle", Printer::print($actual->getName()));
    }

    public function testMemberActions(): void
    {
        $actual = new EnumType("jellicle");
        $actual->addMember("skimbleshanks");
        $actual->addMember(NameToken::from("grizabella"));
        $members = $actual->getMembers();
        $this->assertCount(2, $members);
        $this->assertContainsOnlyInstancesOf(EnumMemberToken::class, $members);

        $actual = new EnumType("jellicle");
        $actual->setMembers($members);
        $members = $actual->getMembers();
        $this->assertCount(2, $members);
        $this->assertContainsOnlyInstancesOf(EnumMemberToken::class, $members);

        $actual = new EnumType("jellicle");
        $actual->setMembers(["skimbleshanks", "grizabella"]);
        $members = $actual->getMembers();
        $this->assertCount(2, $members);
        $this->assertContainsOnlyInstancesOf(EnumMemberToken::class, $members);
    }

    public function testMemberActionFailures(): void
    {
        $actual = new EnumType("jellicle");
        try {
            $actual->addMember("macavity", 1, 2);
            $this->fail("Failed to catch spread operator abuse");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }

        $actual = new EnumType("jellicle");
        try {
            $actual->addMember("macavity", "ginger");
            $actual->addMember("growltiger");
            $this->fail("Failed to require initializer");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }

    public function testToString(): void
    {
        $actual = new EnumType("jellicle");
        $actual->addMember("skimbleshanks");
        $actual->addMember("grizabella");
        $actual->addMember("mungojerrie");
        $actual->hasExport(true);
        $actual->hasConst(true);

        $expected = <<<'ENUM'
export const enum jellicle {
    skimbleshanks,
    grizabella,
    mungojerrie,
}
ENUM;
        $this->assertSame($expected, Printer::print($actual));

        $actual = new EnumType("jellicle");
        $actual->addMember("grizabella", 5);
        $actual->addMember("mungojerrie");
        $actual->addMember("skimbleshanks", "railway cat");
        $actual->hasAmbient(true);

        $expected = <<<'ENUM'
declare enum jellicle {
    grizabella = 5,
    mungojerrie,
    skimbleshanks = "railway cat",
}
ENUM;
        $this->assertSame($expected, Printer::print($actual));
    }

    public function testExport(): void
    {
        $actual = new EnumType("jellicle");
        $actual->hasExport(true);
        $this->assertTrue($actual->isExport());
        $actual->hasExport(false);
        $this->assertFalse($actual->isExport());
    }

    public function testAmbient(): void
    {
        $actual = new EnumType("jellicle");
        $actual->hasAmbient(true);
        $this->assertTrue($actual->isAmbient());
        $actual->hasAmbient(false);
        $this->assertFalse($actual->isAmbient());
    }

    public function testConst(): void
    {
        $actual = new EnumType("jellicle");
        $actual->hasConst(true);
        $this->assertTrue($actual->isConst());
        $actual->hasConst(false);
        $this->assertFalse($actual->isConst());
    }
}
