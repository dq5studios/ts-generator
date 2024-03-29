<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Values;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Types\ObjectType;
use DQ5Studios\TypeScript\Generator\Values\ObjectValue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ObjectValue::class)]
#[CoversClass(Printer::class)]
class ObjectValueTest extends TestCase
{
    public function testConstruct(): void
    {
        $actual = new ObjectValue();
        $this->assertEmpty($actual->getValue());
        $this->assertSame(ObjectType::class, $actual->getType());

        $actual = new ObjectValue(["skimbleshanks", "jennyanydots"]);
        $this->assertCount(2, $actual->getValue());
    }

    public function testToString(): void
    {
        $actual = new ObjectValue();
        $this->assertSame("{}", Printer::print($actual));

        $actual = new ObjectValue(["mungojerrie", "rumpleteazer"]);
        $this->assertSame("{ \"0\": \"mungojerrie\", \"1\": \"rumpleteazer\" }", Printer::print($actual));

        $actual = new ObjectValue(["rumtumtugger" => "jennyanydots", "mungojerrie" => "rumpleteazer"]);
        $this->assertSame("{ \"rumtumtugger\": \"jennyanydots\", \"mungojerrie\": \"rumpleteazer\" }", Printer::print($actual));
    }
}
