<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Tokens\VisibilityToken;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Tokens\VisibilityToken
 */
class VisibilityTokenTest extends TestCase
{
    public function testMethods(): void
    {
        $actual = new VisibilityToken(VisibilityToken::PROTECTED);
        $visibility = $actual->get();
        $this->assertSame(VisibilityToken::PROTECTED, $visibility);

        $actual = new VisibilityToken(VisibilityToken::PRIVATE);
        $actual->set(VisibilityToken::PROTECTED);
        $this->assertSame(VisibilityToken::PROTECTED, $actual->get());
    }
}
