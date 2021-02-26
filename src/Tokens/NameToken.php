<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Error\InvalidName;

/**
 * The name of a thing
 */
class NameToken
{
    /**
     * @throws InvalidName
     */
    public function __construct(protected string $name)
    {
        if (!preg_match("/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/", $name)) {
            throw new InvalidName();
        }
    }

    public static function from(string | NameToken $name): self
    {
        if ($name instanceof NameToken) {
            return $name;
        }
        return new self($name);
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
