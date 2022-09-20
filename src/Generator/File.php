<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator;

use DQ5Studios\TypeScript\Generator\Types\ClassType;
use DQ5Studios\TypeScript\Generator\Types\EnumType;
use DQ5Studios\TypeScript\Generator\Types\FunctionType;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanComment;
use DQ5Studios\TypeScript\Generator\Types\InterfaceType;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasComment;
use DQ5Studios\TypeScript\Generator\Types\Type;

class File implements CanComment
{
    use HasComment;

    /** @var list<Type> */
    protected array $contents = [];

    public function addClass(string $name): ClassType
    {
        return $this->contents[] = new ClassType($name);
    }

    public function addEnum(string $name): EnumType
    {
        return $this->contents[] = new EnumType($name);
    }

    public function addFunction(string $name): FunctionType
    {
        return $this->contents[] = new FunctionType();
    }

    public function addInterface(string $name): InterfaceType
    {
        return $this->contents[] = new InterfaceType($name);
    }

    public function append(Type $type): self
    {
        $this->contents[] = $type;

        return $this;
    }

    /** @return list<Type> */
    public function getContents(): array
    {
        return $this->contents;
    }

    public function __toString(): string
    {
        return (new Printer())->printFile($this);
    }
}
