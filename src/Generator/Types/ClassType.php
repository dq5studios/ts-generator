<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use Attribute;
use DQ5Studios\TypeScript\Generator\Printer;
use DQ5Studios\TypeScript\Generator\Tokens\ClassPropertyToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanAmbient;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanExport;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanExtend;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanImplement;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanIndexSignature;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasAmbient;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasExport;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasExtend;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasImplement;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasIndexSignature;
use DQ5Studios\TypeScript\Generator\Values\NoneValue;
use InvalidArgumentException;

/**
 * The class type, equivalent to PHP class
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ClassType extends ContainerType implements CanExtend, CanImplement, CanExport, CanAmbient, CanIndexSignature
{
    use HasExtend;
    use HasImplement;
    use HasExport;
    use HasAmbient;
    use HasIndexSignature;

    protected string $type = "class";
    /** @var array<string,ClassPropertyToken> */
    protected array $properties = [];

    /**
     * @param class-string<Type>|Type|Type::* $type
     */
    public function addProperty(string|NameToken $name, string|Type $type, mixed $value = null): ClassPropertyToken
    {
        if (is_null($value)) {
            $value = new NoneValue();
        }
        $member = ClassPropertyToken::from($name, $type, $value);

        return $this->properties[Printer::print($member->getName())] = $member;
    }

    /**
     * @return array<string,ClassPropertyToken>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param list<ClassPropertyToken> $properties
     *
     * @throws InvalidArgumentException
     */
    public function setProperties(array $properties): self
    {
        $this->properties = [];
        foreach ($properties as $property) {
            if (!($property instanceof ClassPropertyToken)) {
                throw new InvalidArgumentException();
            }
            $this->addProperty($property->getName(), $property->getType(), $property->getValue());
        }

        return $this;
    }
}
