<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Types\Traits\HasComment
 */
class HasCommentTest extends TestCase
{
    public function testMethods(): void
    {
        /** @var HasComment */
        $mock = $this->getMockForTrait(HasComment::class);
        $this->assertSame("", (string) $mock->getComment());

        /** @var HasComment */
        $mock = $this->getMockForTrait(HasComment::class);
        $mock->setComment("mungojerrie");
        $this->assertSame("/** mungojerrie */", (string) $mock->getComment());

        /** @var HasComment */
        $mock = $this->getMockForTrait(HasComment::class);
        $mock->addComment("rumpelteazer");
        $mock->setComment("mungojerrie");
        $this->assertSame("/** mungojerrie */", (string) $mock->getComment());

        $expected = <<<'comment'
/**
 * mungojerrie
 * rumpelteazer
 */
comment;

        /** @var HasComment */
        $mock = $this->getMockForTrait(HasComment::class);
        $mock->setComment("mungojerrie");
        $mock->addComment("rumpelteazer");
        $this->assertSame($expected, (string) $mock->getComment());

        /** @var HasComment */
        $mock = $this->getMockForTrait(HasComment::class);
        $mock->addComment("mungojerrie\nrumpelteazer");
        $this->assertSame($expected, (string) $mock->getComment());
    }
}
