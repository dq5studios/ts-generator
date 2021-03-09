<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Values;

use DQ5Studios\TypeScript\Generator\Types\ArrayType;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Values\ArrayValue
 */
class ArrayValueTest extends TestCase
{
    public function testConstruct(): void
    {
        $actual = new ArrayValue();
        $this->assertEmpty($actual->getValue());
        $this->assertSame(ArrayType::class, $actual->getType());

        $actual = new ArrayValue(["skimbleshanks", "jennyanydots"]);
        $this->assertCount(2, $actual->getValue());
    }

    public function testToString(): void
    {
        $actual = new ArrayValue();
        $this->assertSame("[]", (string) $actual);

        $actual = new ArrayValue(["mungojerrie", "rumpleteazer"]);
        $this->assertSame("[\"mungojerrie\", \"rumpleteazer\"]", (string) $actual);
    }
}
