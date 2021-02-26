<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use InvalidArgumentException;

/**
 * This is a named type that contains other types
 *
 * @psalm-consistent-constructor
 */
abstract class ContainerType extends Type
{
    protected string $type = "";
    protected NameToken $name;

    public function getName(): NameToken
    {
        return $this->name;
    }

    public function setName(string | NameToken $name): static
    {
        if (is_string($name)) {
            $name = new NameToken($name);
        }
        $this->name = $name;

        return $this;
    }
    /**
     * @throws InvalidArgumentException
     */
    final public function __construct(string | NameToken $name = null)
    {
        if (is_null($name)) {
            throw new InvalidArgumentException("Name required");
        }

        if (is_string($name)) {
            $name = new NameToken($name);
        }
        $this->name = $name;
    }
}
