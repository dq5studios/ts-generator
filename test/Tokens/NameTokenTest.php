<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Error\InvalidName;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\EnumType;
use Error;
use PHPUnit\Framework\TestCase;

class NameTokenTest extends TestCase
{
    /**
     * @covers \DQ5Studios\TypeScript\Generator\Tokens\NameToken
     */
    public function testCreation(): void
    {
        try {
            new NameToken("");
            $this->fail("Failed requiring name");
        } catch (Error $e) {
            $this->assertInstanceOf(InvalidName::class, $e);
        }

        try {
            new EnumType("--invalid");
            $this->fail("Failed validating name");
        } catch (Error $e) {
            $this->assertInstanceOf(InvalidName::class, $e);
        }

        $actual = NameToken::from("pollicle");
        $this->assertSame("pollicle", (string) $actual);
        $actual = NameToken::from($actual);
        $this->assertSame("pollicle", (string) $actual);
        $actual = new NameToken("jellicle");
        $this->assertSame("jellicle", (string) $actual);
    }
}
