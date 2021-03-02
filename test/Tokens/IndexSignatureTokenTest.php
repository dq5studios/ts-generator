<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Tokens\IndexSignatureToken;
use DQ5Studios\TypeScript\Generator\Types\NumberType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Tokens\IndexSignatureToken
 */
class IndexSignatureTokenTest extends TestCase
{
    public function testOf(): void
    {
        $actual = IndexSignatureToken::of("string");
        $this->assertSame("[index: string]", (string) $actual);
    }

    public function testSetType(): void
    {
        $actual = new IndexSignatureToken("skimbleshanks");
        try {
            $actual->setType(Type::ARRAY);
            $this->fail("Failed to prevent invalid type");
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }

        $type = new NumberType();
        $actual->setType($type);
        $this->assertSame("[skimbleshanks: number]", (string) $actual);
        $this->assertSame("number", (string) $actual->getType());
    }
}
