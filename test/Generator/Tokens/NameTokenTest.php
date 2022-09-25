<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\EnumType;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Tokens\NameToken
 */
class NameTokenTest extends TestCase
{
    public function testCreation(): void
    {
        try {
            new NameToken("");
            $this->fail("Failed requiring name");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }

        try {
            new EnumType("--invalid");
            $this->fail("Failed validating name");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }

        $actual = NameToken::from("pollicle");
        $this->assertSame("pollicle", Printer::print($actual));
        $actual = NameToken::from($actual);
        $this->assertSame("pollicle", Printer::print($actual));
        $actual = new NameToken("jellicle");
        $this->assertSame("jellicle", Printer::print($actual));

        $actual = new NameToken("jellicle");
        $this->assertSame("jellicle", $actual->getName());
    }
}
