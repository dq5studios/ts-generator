<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

use DQ5Studios\TypeScript\Generator\Types\ClassType;
use DQ5Studios\TypeScript\Generator\Types\InterfaceType;
use InvalidArgumentException;

/**
 * Extendable
 */
trait HasExtend
{
    /** @var list<InterfaceType|ClassType> */
    protected array $extend = [];

    /** @param InterfaceType|ClassType $extend */
    public function addExtend(InterfaceType | ClassType $extend): InterfaceType | ClassType
    {
        $this->extend[] = $extend;
        return $extend;
    }

    /** @return list<InterfaceType|ClassType> */
    public function getExtend(): array
    {
        return $this->extend;
    }

    /** @param list<InterfaceType|ClassType> $extend */
    public function setExtend(array $extend): self
    {
        $this->extend = [];
        foreach ($extend as $ext) {
            $this->addExtend($ext);
        }
        return $this;
    }
}
