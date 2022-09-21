<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

use DQ5Studios\TypeScript\Generator\Types\ClassType;
use DQ5Studios\TypeScript\Generator\Types\InterfaceType;

/**
 * Implements
 */
trait HasImplement
{
    /** @var list<InterfaceType|ClassType> */
    protected array $implement = [];

    public function addImplement(InterfaceType|ClassType $implement): InterfaceType|ClassType
    {
        $this->implement[] = $implement;

        return $implement;
    }

    /** @return list<InterfaceType|ClassType> */
    public function getImplement(): array
    {
        return $this->implement;
    }

    /** @param list<InterfaceType|ClassType> $implement */
    public function setImplement(array $implement): self
    {
        $this->implement = [];
        foreach ($implement as $ext) {
            $this->addImplement($ext);
        }

        return $this;
    }
}
