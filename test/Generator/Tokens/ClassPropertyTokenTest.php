<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Tokens\ClassPropertyToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use DQ5Studios\TypeScript\Generator\Types\UnionType;
use DQ5Studios\TypeScript\Generator\Values\StringValue;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Tokens\ClassPropertyToken
 * @covers \DQ5Studios\TypeScript\Generator\Tokens\MemberToken
 */
class ClassPropertyTokenTest extends TestCase
{
    public function testToString(): void
    {
        $actual = ClassPropertyToken::from(NameToken::from("skimbleshanks"), StringType::class, "the railway cat");
        $this->assertSame("skimbleshanks: string = \"the railway cat\"", (string) $actual);

        $actual = ClassPropertyToken::from(NameToken::from("skimbleshanks"), new StringType())->hasReadonly(true);
        $this->assertSame("readonly skimbleshanks: string", (string) $actual);
        $this->assertTrue($actual->isReadonly());

        $actual = ClassPropertyToken::from("skimbleshanks", new StringType(), readonly: true);
        $this->assertSame("readonly skimbleshanks: string", (string) $actual);
        $this->assertTrue($actual->isReadonly());

        $actual = ClassPropertyToken::from(NameToken::from("skimbleshanks"), new StringType())->hasStatic(true);
        $this->assertSame("static skimbleshanks: string", (string) $actual);
        $this->assertTrue($actual->isStatic());

        $actual = ClassPropertyToken::from("skimbleshanks", new StringType(), static: true);
        $this->assertSame("static skimbleshanks: string", (string) $actual);
        $this->assertTrue($actual->isStatic());

        $actual = ClassPropertyToken::from("skimbleshanks", (new StringValue("railway cat"))->asLiteral());
        $this->assertSame("skimbleshanks: \"railway cat\"", (string) $actual);

        $actual = ClassPropertyToken::from("skimbleshanks", new StringType(), optional: true);
        $this->assertSame("skimbleshanks?: string", (string) $actual);
        $this->assertTrue($actual->isOptional());

        $actual = ClassPropertyToken::from("skimbleshanks?", (new StringValue("railway cat"))->asLiteral());
        $this->assertSame("skimbleshanks?: \"railway cat\"", (string) $actual);
        $this->assertTrue($actual->isOptional());

        $types = UnionType::of(
            (new StringValue("the railway cat"))->asLiteral(),
            (new StringValue("the cat of the railway"))->asLiteral()
        );
        $this->assertSame("(\"the railway cat\" | \"the cat of the railway\")", (string) $types);
        $actual = ClassPropertyToken::from("skimbleshanks", $types);
        $this->assertSame("skimbleshanks: (\"the railway cat\" | \"the cat of the railway\")", (string) $actual);
    }
}
