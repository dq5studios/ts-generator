<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Types\Traits;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasComment;
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
        $this->assertSame("", Printer::print($mock->getComment()));

        /** @var HasComment */
        $mock = $this->getMockForTrait(HasComment::class);
        $mock->setComment("mungojerrie");
        $this->assertSame("/** mungojerrie */", Printer::print($mock->getComment()));

        /** @var HasComment */
        $mock = $this->getMockForTrait(HasComment::class);
        $mock->addComment("rumpelteazer");
        $mock->setComment("mungojerrie");
        $this->assertSame("/** mungojerrie */", Printer::print($mock->getComment()));

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
        $this->assertSame($expected, Printer::print($mock->getComment()));

        /** @var HasComment */
        $mock = $this->getMockForTrait(HasComment::class);
        $mock->addComment("mungojerrie\nrumpelteazer");
        $this->assertSame($expected, Printer::print($mock->getComment()));
    }
}
