<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Tokens\CommentToken;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Tokens\CommentToken
 * @covers \DQ5Studios\TypeScript\Generator\Printer
 */
class CommentTokenTest extends TestCase
{
    public function testMethods(): void
    {
        $actual = new CommentToken("mungojerrie");
        $comment = $actual->get();
        $this->assertSame("mungojerrie", $comment);

        $actual = new CommentToken("mungojerrie");
        $actual->set("rumpelteazer");
        $this->assertSame("/** rumpelteazer */", Printer::print($actual));

        $actual = new CommentToken("mungojerrie");
        $actual->expand("rumpelteazer");
        $expected = <<<'comment'
            /**
             * mungojerrie
             * rumpelteazer
             */
            comment;
        $this->assertSame($expected, Printer::print($actual));
    }

    public function testToString(): void
    {
        $actual = new CommentToken("");
        $this->assertSame("", Printer::print($actual));

        $actual = new CommentToken("skimbleshanks");
        $this->assertSame("/** skimbleshanks */", Printer::print($actual));

        $actual = new CommentToken("skimbleshanks\nthe railway cat");
        $expected = <<<'comment'
            /**
             * skimbleshanks
             * the railway cat
             */
            comment;
        $this->assertSame($expected, Printer::print($actual));
    }
}
