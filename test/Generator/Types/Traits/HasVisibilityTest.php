<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Types\Traits;

use DQ5Studios\TypeScript\Generator\Tokens\VisibilityToken;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasVisibility;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Types\Traits\HasVisibility
 */
class HasVisibilityTest extends TestCase
{
    public function testMethods(): void
    {
        /** @var HasVisibility */
        $mock = $this->getMockForTrait(HasVisibility::class);
        $this->assertNull($mock->getVisibility());

        /** @var HasVisibility */
        $mock = $this->getMockForTrait(HasVisibility::class);
        $mock->setVisibility(VisibilityToken::PUBLIC);
        $this->assertSame(VisibilityToken::PUBLIC, $mock->getVisibility()->get());

        $mock->setVisibility(VisibilityToken::PROTECTED);
        $this->assertSame(VisibilityToken::PROTECTED, $mock->getVisibility()->get());

        $mock->setVisibility(new VisibilityToken(VisibilityToken::PRIVATE));
        $this->assertSame(VisibilityToken::PRIVATE, $mock->getVisibility()->get());
    }
}
