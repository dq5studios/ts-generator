<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanComment;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanName;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasComment;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasName;
use InvalidArgumentException;

/**
 * This is a named type that contains other types
 */
abstract class ContainerType extends Type implements CanName, CanComment
{
    use HasName;
    use HasComment;

    protected string $type = "";

    /**
     * @throws InvalidArgumentException
     */
    final public function __construct(string|NameToken $name = null)
    {
        if (is_null($name)) {
            throw new InvalidArgumentException("Name required");
        }

        $name = NameToken::from($name);
        $this->name = $name;
    }
}
