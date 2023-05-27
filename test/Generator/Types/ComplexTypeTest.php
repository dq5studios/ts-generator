<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Types;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Types\FunctionType;
use DQ5Studios\TypeScript\Generator\Types\ObjectType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(FunctionType::class)]
#[CoversClass(ObjectType::class)]
class ComplexTypeTest extends TestCase
{
    public static function typeList(): Generator
    {
        yield "Function" => [FunctionType::class, "Function"];
        yield "Object" => [ObjectType::class, "object"];
    }

    /**
     * @param class-string<Type> $class
     */
    #[DataProvider(typeList::class)]
    public function testToString(string $class, string $expected): void
    {
        $type = new $class();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertSame($expected, Printer::print($type));
    }
}
