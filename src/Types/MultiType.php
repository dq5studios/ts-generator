<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use UnexpectedValueException;

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
     * @param list<Type|class-string<Type>> $types
     */
    public static function from(string | Type ...$types): static
    {
        $new = new static();
        $new->contains(...$types);
        return $new;
    }

    /**
     * @param list<Type|class-string<Type>> $types
     * @throws UnexpectedValueException
     */
    public function contains(string | Type ...$types): self
    {
        foreach ($types as $type) {
            if ($type instanceof Type) {
                $this->is[] = $type;
            } else {
                if (!is_subclass_of($type, Type::class)) {
                    throw new UnexpectedValueException();
                }
                $this->is[] = new $type();
            }
        }
        return $this;
    }

    public function __toString(): string
    {
        return join(static::$sep, $this->is);
    }
}
