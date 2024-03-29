<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Values;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Types\ArrayType;
use DQ5Studios\TypeScript\Generator\Values\ArrayValue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ArrayValue::class)]
#[CoversClass(Printer::class)]
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
        $this->assertSame("[]", Printer::print($actual));

        $actual = new ArrayValue(["mungojerrie", "rumpleteazer"]);
        $this->assertSame("[\"mungojerrie\", \"rumpleteazer\"]", Printer::print($actual));
    }
}
