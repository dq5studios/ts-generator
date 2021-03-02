<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Tokens\ObjectPropertyToken;
use DQ5Studios\TypeScript\Generator\Values\StringValue;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Types\ObjectType
 */
class ObjectTypeTest extends TestCase
{
    public function testMemberActions(): void
    {
        $actual = new ObjectType();
        $actual->addProperty("skimbleshanks", StringType::class);
        $actual->addProperty(NameToken::from("grizabella"), new NumberType());
        $members = $actual->getProperties();
        $this->assertCount(2, $members);
        $this->assertContainsOnlyInstancesOf(ObjectPropertyToken::class, $members);

        $actual = new ObjectType();
        $actual->setProperties($members);
        $members = $actual->getProperties();
        $this->assertCount(2, $members);
        $this->assertContainsOnlyInstancesOf(ObjectPropertyToken::class, $members);
    }

    public function testMemberActionFailures(): void
    {
        $actual = new ObjectType();
        try {
            /** @psalm-suppress InvalidArgument */
            $actual->setProperties(["macavity", "ginger"]);
            $this->fail("Failed to validate property");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }

    public function testToString(): void
    {
        $actual = new ObjectType();
        $this->assertSame("object", (string) $actual);

        $actual = new ObjectType();
        $actual->addProperty("skimbleshanks", NumberType::class);
        $actual->addProperty("grizabella", new StringType());
        $actual->addProperty(
            "growltiger",
            UnionType::of(
                (new StringValue("mungojerrie"))->asLiteral(),
                (new StringValue("rumpelteazer"))->asLiteral()
            )
        );

        $expected = <<<'ENUM'
{
    skimbleshanks: number,
    grizabella: string,
    growltiger: "mungojerrie" | "rumpelteazer",
}
ENUM;
        $this->assertSame($expected, (string) $actual);

        $actual = new ObjectType();
        $actual->addProperty("grizabella", StringType::class);
        $actual->addProperty("mungojerrie", NumberType::class);
        $actual->addProperty("skimbleshanks", UnionType::of(StringType::class, NumberType::class));
        $actual->addComment("jellicles can\n\njellicles do");

        $expected = <<<'ENUM'
/**
 * jellicles can
 *
 * jellicles do
 */
{
    grizabella: string,
    mungojerrie: number,
    skimbleshanks: string | number,
}
ENUM;
        $this->assertSame($expected, (string) $actual);

        $actual = new ObjectType();
        $subtype = new ObjectType();
        $subtype->addProperty("mungojerrie", NumberType::class);
        $actual->addProperty("rumpelteazer", $subtype);

        $expected = <<<'ENUM'
{
    rumpelteazer: {
        mungojerrie: number,
    },
}
ENUM;
        $this->assertSame($expected, (string) $actual);
    }
}
