<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

/**
 * The base code for union and intersection types
 */
abstract class MultiType extends Type
{
    /** @var list<Type> */
    protected array $is = [];

    protected static string $sep = "";
    protected string $type = "";

    public function setSeperator(string $seperator): self
    {
        static::$sep = $seperator;
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

    public function __toString(): string
    {
        return join(static::$sep, $this->is);
    }
}
