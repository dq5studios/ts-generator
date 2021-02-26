<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Tokens\CommentTokenTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Tokens\CommentTokenTrait
 */
class CommentTokenTraitTest extends TestCase
{
    public function testMethods(): void
    {
        /** @var CommentTokenTrait */
        $mock = $this->getMockForTrait(CommentTokenTrait::class);
        $this->assertSame("", (string) $mock->getComment());

        /** @var CommentTokenTrait */
        $mock = $this->getMockForTrait(CommentTokenTrait::class);
        $mock->setComment("mungojerrie");
        $this->assertSame("/** mungojerrie */", (string) $mock->getComment());

        /** @var CommentTokenTrait */
        $mock = $this->getMockForTrait(CommentTokenTrait::class);
        $mock->addComment("rumpelteazer");
        $mock->setComment("mungojerrie");
        $this->assertSame("/** mungojerrie */", (string) $mock->getComment());

        $expected = <<<'comment'
/**
 * mungojerrie
 * rumpelteazer
 */
comment;

        /** @var CommentTokenTrait */
        $mock = $this->getMockForTrait(CommentTokenTrait::class);
        $mock->setComment("mungojerrie");
        $mock->addComment("rumpelteazer");
        $this->assertSame($expected, (string) $mock->getComment());

        /** @var CommentTokenTrait */
        $mock = $this->getMockForTrait(CommentTokenTrait::class);
        $mock->addComment("mungojerrie\nrumpelteazer");
        $this->assertSame($expected, (string) $mock->getComment());
    }
}
