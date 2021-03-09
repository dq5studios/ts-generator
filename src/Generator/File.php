<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator;

use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanComment;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasComment;
use DQ5Studios\TypeScript\Generator\Types\Type;

class File implements CanComment
{
    use HasComment;

    /** @var list<Type> */
    protected array $contents = [];

    public function add(Type $type): self
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
