<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use Attribute;
use DQ5Studios\TypeScript\Generator\Tokens\InterfacePropertyToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanAmbient;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanExport;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanExtend;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanFunctionSignature;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanIndexSignature;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasAmbient;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasExport;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasExtend;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasFunctionSignature;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasIndexSignature;
use InvalidArgumentException;

/**
 * The interface type, no PHP equivalent
 */
#[Attribute(Attribute::TARGET_CLASS)]
class InterfaceType extends ContainerType implements CanExtend, CanExport, CanAmbient, CanIndexSignature, CanFunctionSignature
{
    use HasExtend;
    use HasExport;
    use HasAmbient;
    use HasIndexSignature;
    use HasFunctionSignature;

    protected string $type = "interface";
    /** @var array<string,InterfacePropertyToken> */
    protected array $properties = [];

    /**
     * @param class-string<Type>|Type|Type::* $type
     */
    public function addProperty(string|NameToken $name, string|Type $type): InterfacePropertyToken
    {
        $member = InterfacePropertyToken::from($name, $type);

        return $this->properties[(string) $member->getName()] = $member;
    }

    /**
     * @return array<string,InterfacePropertyToken>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param list<InterfacePropertyToken> $properties
     *
     * @throws InvalidArgumentException
     */
    public function setProperties(array $properties): self
    {
        $this->properties = [];
        foreach ($properties as $property) {
            if (!($property instanceof InterfacePropertyToken)) {
                throw new InvalidArgumentException();
            }
            $this->addProperty($property->getName(), $property->getType());
        }

        return $this;
    }
}
