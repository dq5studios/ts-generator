<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Tokens\ObjectPropertyToken;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Tokens\ObjectPropertyToken
 * @covers \DQ5Studios\TypeScript\Generator\Tokens\MemberToken
 */
class ObjectPropertyTokenTest extends TestCase
{
    public function testToString(): void
    {
        $actual = ObjectPropertyToken::from("skimbleshanks", new StringType());
        $this->assertSame("skimbleshanks: string", (string) $actual);

        $actual = ObjectPropertyToken::from("skimbleshanks?", new StringType());
        $this->assertSame("skimbleshanks?: string", (string) $actual);
        $this->assertTrue($actual->isOptional());

        $actual = ObjectPropertyToken::from(NameToken::from("skimbleshanks"), StringType::class);
        $this->assertSame("skimbleshanks: string", (string) $actual);

        try {
            ObjectPropertyToken::from("macavity", "invalid");
            $this->fail("Failed to reject invalid type");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }
}
