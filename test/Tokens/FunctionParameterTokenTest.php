<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Tokens\FunctionParameterToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use Exception;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Tokens\FunctionParameterToken
 */
class FunctionParameterTokenTest extends TestCase
{
    public function testToString(): void
    {
        $actual = FunctionParameterToken::from("skimbleshanks", new StringType());
        $this->assertSame("skimbleshanks: string", (string) $actual);

        $actual = FunctionParameterToken::from(NameToken::from("skimbleshanks"), StringType::class);
        $this->assertSame("skimbleshanks: string", (string) $actual);

        try {
            $actual = FunctionParameterToken::from("macavity", "invalid");
            $this->fail("Failed to reject invalid type");
        } catch (Exception $e) {
            $this->assertInstanceOf(UnexpectedValueException::class, $e);
        }
    }
}
