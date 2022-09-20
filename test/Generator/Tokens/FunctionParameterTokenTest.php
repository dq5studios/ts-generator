<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Types\ArrayType;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Tokens\FunctionParameterToken
 * @covers \DQ5Studios\TypeScript\Generator\Tokens\MemberToken
 */
class FunctionParameterTokenTest extends TestCase
{
    public function testToString(): void
    {
        $actual = FunctionParameterToken::from("skimbleshanks", new StringType());
        $this->assertSame("skimbleshanks: string", (string) $actual);

        $actual = FunctionParameterToken::from("skimbleshanks?", new StringType());
        $this->assertSame("skimbleshanks?: string", (string) $actual);
        $this->assertTrue($actual->isOptional());

        $actual = FunctionParameterToken::from("...skimbleshanks?", ArrayType::of(Type::NUMBER));
        $this->assertSame("...skimbleshanks?: number[]", (string) $actual);
        $this->assertTrue($actual->isOptional());
        $this->assertTrue($actual->isSpread());

        $actual = FunctionParameterToken::from(NameToken::from("skimbleshanks"), StringType::class);
        $this->assertSame("skimbleshanks: string", (string) $actual);

        try {
            /** @psalm-suppress ArgumentTypeCoercion */
            $actual = FunctionParameterToken::from("macavity", "invalid");
            $this->fail("Failed to reject invalid type");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }
}
