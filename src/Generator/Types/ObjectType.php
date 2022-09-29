<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Tokens\ObjectPropertyToken;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanComment;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanFunctionSignature;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanIndexSignature;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasComment;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasFunctionSignature;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasIndexSignature;
use InvalidArgumentException;

/**
 * The object type, equivalent to PHP object
 */
class ObjectType extends ComplexType implements CanComment, CanIndexSignature, CanFunctionSignature
{
    use HasComment;
    use HasIndexSignature;
    use HasFunctionSignature;

    protected string $type = "object";
    /** @var array<string,ObjectPropertyToken> */
    protected array $properties = [];

    /**
     * @param class-string<Type>|Type|Type::* $type
     */
    public function addProperty(string|NameToken $name, string|Type $type): ObjectPropertyToken
    {
        $member = ObjectPropertyToken::from($name, $type);

        return $this->properties[$member->getName()->getName() . "_" . count($this->properties)] = $member;
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
     *
     * @throws InvalidArgumentException
     */
    public function setProperties(array $properties): self
    {
        $this->properties = [];
        foreach ($properties as $property) {
            if (!($property instanceof ObjectPropertyToken)) {
                throw new InvalidArgumentException();
            }
            $this->addProperty($property->getName(), $property->getType());
        }

        return $this;
    }
}
