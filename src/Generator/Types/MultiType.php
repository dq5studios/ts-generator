<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use DQ5Studios\TypeScript\Generator\Printer;

/**
 * The base code for union and intersection types
 */
abstract class MultiType extends Type
{
    /** @var list<Type> */
    protected array $is = [];

    protected string $sep = "";
    protected string $type = "";

    public function getSeperator(): string
    {
        return $this->sep;
    }

    public function setSeperator(string $seperator): self
    {
        $this->sep = $seperator;
        return $this;
    }

    /**
     * @param class-string<Type>|Type|Type::* $types
     */
    public static function of(string | Type ...$types): self
    {
        $new = new static();
        return $new->contains(...$types);
    }

    /**
     * @param class-string<Type>|Type|Type::* $types
     */
    public function contains(string | Type ...$types): self
    {
        foreach ($types as $type) {
            $this->is[] = Type::from($type);
        }
        return $this;
    }

    /** @return list<Type> */
    public function getContents(): array
    {
        return $this->is;
    }
}
