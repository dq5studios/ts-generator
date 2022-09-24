<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Tokens\MemberToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\EnumType;
use DQ5Studios\TypeScript\Generator\Types\NoneType;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use DQ5Studios\TypeScript\Generator\Values\StringValue;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Tokens\MemberToken
 */
class MemberTokenTest extends TestCase
{
    public function testCreation(): void
    {
        $name = new NameToken("skimbleshanks");
        $type = new StringType();
        $value = new StringValue("railway cat");
        $actual = new MemberToken($name, $type, $value);

        $this->assertSame($name, $actual->getName());
        $this->assertSame($type, $actual->getType());
        $this->assertSame($value, $actual->getValue());
    }

    public function testSet(): void
    {
        $actual = new MemberToken(NameToken::from("_"));
        $name = new NameToken("skimbleshanks");
        $is = $actual->setName($name);
        $this->assertInstanceOf(MemberToken::class, $is);
        $type = new StringType();
        $is = $actual->setType($type);
        $this->assertInstanceOf(MemberToken::class, $is);
        $value = new StringValue("railway cat");
        $is = $actual->setValue($value);
        $this->assertInstanceOf(MemberToken::class, $is);

        $this->assertSame($name, $actual->getName());
        $this->assertSame($type, $actual->getType());
        $this->assertSame($value, $actual->getValue());
    }

    public function testToString(): void
    {
        $actual = new MemberToken(NameToken::from("skimbleshanks"));
        $this->assertSame("skimbleshanks", (string) $actual);

        $type = new EnumType("jellicles");
        $actual->setType($type);
        $this->assertSame("skimbleshanks: jellicles", (string) $actual);

        $type = new StringType();
        $actual->setType($type);
        $this->assertSame("skimbleshanks: string", (string) $actual);

        $value = new StringValue("railway cat");
        $actual->setValue($value);
        $this->assertSame("skimbleshanks: string = \"railway cat\"", (string) $actual);

        $type = new NoneType();
        $actual->setType($type);
        $this->assertSame("skimbleshanks = \"railway cat\"", (string) $actual);

        $type = new NoneType();
        $actual->setType($type);
        $this->assertSame("skimbleshanks = \"railway cat\"", (string) $actual);
    }
}
