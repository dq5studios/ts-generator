<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Printer;
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
        $this->assertSame("skimbleshanks: string", Printer::print($actual));

        $actual = InterfacePropertyToken::from(NameToken::from("skimbleshanks"), new StringType())->hasReadonly(true);
        $this->assertSame("readonly skimbleshanks: string", Printer::print($actual));
        $this->assertTrue($actual->isReadonly());

        $actual = InterfacePropertyToken::from("skimbleshanks", (new StringValue("railway cat"))->asLiteral());
        $this->assertSame("skimbleshanks: \"railway cat\"", Printer::print($actual));

        $actual = InterfacePropertyToken::from("skimbleshanks?", (new StringValue("railway cat"))->asLiteral());
        $this->assertSame("skimbleshanks?: \"railway cat\"", Printer::print($actual));
        $this->assertTrue($actual->isOptional());

        $types = UnionType::of(
            (new StringValue("the railway cat"))->asLiteral(),
            (new StringValue("the cat of the railway"))->asLiteral()
        );
        $this->assertSame("(\"the railway cat\" | \"the cat of the railway\")", Printer::print($types));
        $actual = InterfacePropertyToken::from("skimbleshanks", $types);
        $this->assertSame("skimbleshanks: (\"the railway cat\" | \"the cat of the railway\")", Printer::print($actual));
    }
}
