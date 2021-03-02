<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Tokens\InterfacePropertyToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use DQ5Studios\TypeScript\Generator\Types\UnionType;
use DQ5Studios\TypeScript\Generator\Values\StringValue;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Tokens\InterfacePropertyToken
 * @covers \DQ5Studios\TypeScript\Generator\Tokens\MemberToken
 */
class InterfacePropertyTokenTest extends TestCase
{
    public function testToString(): void
    {
        $actual = InterfacePropertyToken::from(NameToken::from("skimbleshanks"), StringType::class);
        $this->assertSame("skimbleshanks: string", (string) $actual);

        $actual = InterfacePropertyToken::from(NameToken::from("skimbleshanks"), new StringType());
        $this->assertSame("skimbleshanks: string", (string) $actual);

        $actual = InterfacePropertyToken::from("skimbleshanks", (new StringValue("railway cat"))->asLiteral());
        $this->assertSame("skimbleshanks: \"railway cat\"", (string) $actual);

        $actual = InterfacePropertyToken::from("skimbleshanks?", (new StringValue("railway cat"))->asLiteral());
        $this->assertSame("skimbleshanks?: \"railway cat\"", (string) $actual);
        $this->assertTrue($actual->isOptional());

        $types = UnionType::of(
            (new StringValue("the railway cat"))->asLiteral(),
            (new StringValue("the cat of the railway"))->asLiteral()
        );
        $this->assertSame("\"the railway cat\" | \"the cat of the railway\"", (string) $types);
        $actual = InterfacePropertyToken::from("skimbleshanks", $types);
        $this->assertSame("skimbleshanks: \"the railway cat\" | \"the cat of the railway\"", (string) $actual);
    }
}
