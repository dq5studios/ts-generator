<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Tokens\ObjectPropertyToken;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanComment;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasComment;
use InvalidArgumentException;

/**
 * The object type, equivalent to PHP object
 */
class ObjectType extends ComplexType implements CanComment
{
    use HasComment;

    protected string $type = "object";
    /** @var array<string,ObjectPropertyToken> */
    protected array $properties = [];

    /**
     * @param class-string<Type>|Type|Type::* $type
     * @throws InvalidArgumentException
     */
    public function addProperty(string | NameToken $name, string | Type $type): ObjectPropertyToken
    {
        $member = ObjectPropertyToken::from($name, $type);
        return $this->properties[(string) $member->getName()] = $member;
    }

    /**
     * @return array<string,ObjectPropertyToken>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param list<ObjectPropertyToken> $properties
     * @throws InvalidArgumentException
     */
    public function setProperties(array $properties): self
    {
        $this->properties = [];
        foreach ($properties as $property) {
            /** @psalm-suppress DocblockTypeContradiction */
            if (!($property instanceof ObjectPropertyToken)) {
                throw new InvalidArgumentException();
            }
            $this->addProperty($property->getName(), $property->getType());
        }
        return $this;
    }

    public function __toString(): string
    {
        $comment = (string) $this->getComment();
        $output = !empty($comment) ? "{$comment}\n" : "";
        if (empty($this->properties)) {
            return $output . $this->type;
        }
        $output .= "{\n";
        foreach ($this->properties as $prop) {
            $output .= preg_replace("/^(.*)$/m", "    $1", (string) $prop) . ",\n";
        }
        $output .= "}";
        return $output;
    }
}
