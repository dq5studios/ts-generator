<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Tokens\EnumMemberToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Values\NoneValue;
use DQ5Studios\TypeScript\Generator\Values\NullValue;
use DQ5Studios\TypeScript\Generator\Values\NumberValue;
use DQ5Studios\TypeScript\Generator\Values\StringValue;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Tokens\EnumMemberToken
 */
class EnumMemberTokenTest extends TestCase
{
    public function testToString(): void
    {
        $actual = EnumMemberToken::from(NameToken::from("skimbleshanks"), "railway cat");
        $this->assertSame("skimbleshanks = \"railway cat\"", (string) $actual);

        $actual = EnumMemberToken::from(NameToken::from("skimbleshanks"), 42);
        $this->assertSame("skimbleshanks = 42", (string) $actual);

        $actual = EnumMemberToken::from("skimbleshanks", new StringValue("railway cat"));
        $this->assertSame("skimbleshanks = \"railway cat\"", (string) $actual);

        $actual = EnumMemberToken::from("skimbleshanks", new NumberValue(42));
        $this->assertSame("skimbleshanks = 42", (string) $actual);

        $actual = EnumMemberToken::from("skimbleshanks", new NoneValue());
        $this->assertSame("skimbleshanks", (string) $actual);

        try {
            $actual = EnumMemberToken::from("macavity", 1, 2);
            $this->fail("Failed to catch spread operator abuse");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }

        try {
            $actual = EnumMemberToken::from("macavity", new NullValue());
            $this->fail("Failed to catch computed value");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }
}
