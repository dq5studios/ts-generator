<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Interfaces;

use DQ5Studios\TypeScript\Generator\Types\ClassType;
use DQ5Studios\TypeScript\Generator\Types\InterfaceType;

/**
 * Extendable
 */
interface CanExtend
{
    public function addExtend(InterfaceType|ClassType $extend): InterfaceType|ClassType;

    /** @return list<InterfaceType|ClassType> */
    public function getExtend(): array;

    /** @param list<InterfaceType|ClassType> $extend */
    public function setExtend(array $extend): self;
}
