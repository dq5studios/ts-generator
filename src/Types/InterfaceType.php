<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use DQ5Studios\TypeScript\Generator\Tokens\FunctionSignatureToken;
use DQ5Studios\TypeScript\Generator\Tokens\IndexSignatureToken;
use DQ5Studios\TypeScript\Generator\Tokens\InterfacePropertyToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanExtend;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanName;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasExtend;
use InvalidArgumentException;

/**
 * The interface type, no PHP equivalent
 */
class InterfaceType extends ContainerType implements CanExtend
{
    use HasExtend;

    protected string $type = "interface";
    /** @var array<string,InterfacePropertyToken> */
    protected array $properties = [];
    protected bool $export = false;
    protected bool $ambient = false;

    /**
     * @param class-string<Type>|Type|Type::* $index
     * @param class-string<Type>|Type|Type::* $type
     */
    public function addIndexSignature(string | Type $index, string | Type $type): InterfacePropertyToken
    {
        $name = IndexSignatureToken::of($index);
        return $this->addProperty($name, $type);
    }

    /**
     * @param class-string<Type>|Type|Type::* $types
     */
    public function addCallableSignature(string | Type ...$types): InterfacePropertyToken
    {
        $type = array_pop($types);
        $name = FunctionSignatureToken::of(...$types);
        return $this->addProperty($name, $type);
    }

    /**
     * @param class-string<Type>|Type|Type::* $types
     */
    public function addConstructorSignature(string | Type ...$types): InterfacePropertyToken
    {
        $type = array_pop($types);
        $name = FunctionSignatureToken::of(...$types)->setConstructor(true);
        return $this->addProperty($name, $type);
    }

    /**
     * @param class-string<Type>|Type|Type::* $type
     */
    public function addProperty(string | NameToken $name, string | Type $type): InterfacePropertyToken
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
     * @throws InvalidArgumentException
     */
    public function setProperties(array $properties): self
    {
        $this->properties = [];
        foreach ($properties as $property) {
            /** @psalm-suppress DocblockTypeContradiction */
            if (!($property instanceof InterfacePropertyToken)) {
                throw new InvalidArgumentException();
            }
            $this->addProperty($property->getName(), $property->getType());
        }
        return $this;
    }

    public function isExport(): bool
    {
        return $this->export;
    }

    public function setExport(bool $enable): self
    {
        $this->export = $enable;
        return $this;
    }

    public function isAmbient(): bool
    {
        return $this->ambient;
    }

    public function setAmbient(bool $enable): self
    {
        $this->ambient = $enable;
        return $this;
    }

    public function __toString(): string
    {
        $comment = (string) $this->getComment();
        $output = !empty($comment) ? "{$comment}\n" : "";
        $output .= $this->export ? "export " : "";
        $output .= $this->ambient ? "declare " : "";
        $output .= "interface {$this->name}";
        if (!empty($this->extend)) {
            $extends = array_map(
                fn(CanName $type): string => (string) $type->getName(),
                $this->extend,
            );
            $output .= " extends " . implode(", ", $extends);
        }
        $output .= " {\n";
        foreach ($this->properties as $prop) {
            $output .= preg_replace("/^(.*)$/m", "    $1", (string) $prop) . ",\n";
        }
        $output .= "}";
        return $output;
    }
}
