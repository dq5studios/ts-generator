<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests\Generator\Types\Traits;

use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasName;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DQ5Studios\TypeScript\Generator\Types\Traits\HasName
 */
class HasNameTest extends TestCase
{
    public function testMethods(): void
    {
        /** @var HasName */
        $mock = $this->getMockForTrait(HasName::class);
        try {
            (string) $mock->getName();
        } catch (InvalidArgumentException $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }

        /** @var HasName */
        $mock = $this->getMockForTrait(HasName::class);
        $mock->addName("rumpelteazer");
        $this->assertSame("rumpelteazer", (string) $mock->getName());

        /** @var HasName */
        $mock = $this->getMockForTrait(HasName::class);
        $token = NameToken::from("skimbleshanks");
        $mock->addName($token);
        $this->assertSame("skimbleshanks", (string) $mock->getName());

        /** @var HasName */
        $mock = $this->getMockForTrait(HasName::class);
        $mock->setName("mungojerrie");
        $this->assertSame("mungojerrie", (string) $mock->getName());

        /** @var HasName */
        $mock = $this->getMockForTrait(HasName::class);
        $token = NameToken::from("skimbleshanks");
        $mock->setName($token);
        $this->assertSame("skimbleshanks", (string) $mock->getName());
    }
}
