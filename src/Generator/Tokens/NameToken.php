<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use InvalidArgumentException;

/**
 * The name of a thing
 */
class NameToken
{
    /**
     * @throws InvalidArgumentException
     */
    final public function __construct(protected string $name)
    {
        if (!preg_match("/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/", $name)) {
            throw new InvalidArgumentException("{$name} is an invalid name");
        }
    }

    public static function from(string|NameToken $name): self
    {
        if ($name instanceof self) {
            return $name;
        }

        return new self($name);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
