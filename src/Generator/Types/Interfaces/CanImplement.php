<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Interfaces;

use DQ5Studios\TypeScript\Generator\Types\ClassType;
use DQ5Studios\TypeScript\Generator\Types\InterfaceType;

/**
 * Implements
 */
interface CanImplement
{
    /** @param InterfaceType $implement */
    public function addImplement(InterfaceType $implement): InterfaceType | ClassType;
    /** @return list<InterfaceType|ClassType> */
    public function getImplement(): array;
    /** @param list<InterfaceType|ClassType> $implement */
    public function setImplement(array $implement): self;
}
