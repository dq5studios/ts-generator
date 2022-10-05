<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use Attribute;
use DQ5Studios\TypeScript\Generator\Tokens\EnumMemberToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanAmbient;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanExport;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasAmbient;
use DQ5Studios\TypeScript\Generator\Types\Traits\HasExport;
use DQ5Studios\TypeScript\Generator\Values\NoneValue;
use DQ5Studios\TypeScript\Generator\Values\NumberValue;
use DQ5Studios\TypeScript\Generator\Values\Value;
use InvalidArgumentException;

/**
 * The enum type, equivalent to PHP enum
 */
#[Attribute(Attribute::TARGET_CLASS)]
class EnumType extends ContainerType implements CanExport, CanAmbient
{
    use HasAmbient;
    use HasExport;

    protected string $type = "enum";
    /** @var array<string,EnumMemberToken> */
    protected array $members = [];
    protected bool $const = false;

    private bool $can_auto = true;

    /**
     * @throws InvalidArgumentException
     */
    public function addMember(string|NameToken $name, int|float|string|Value ...$value): EnumMemberToken
    {
        if (\count($value) > 1) {
            throw new InvalidArgumentException("No more that one value can be assigned at a time");
        }
        if (empty($value) && !$this->can_auto) {
            throw new InvalidArgumentException("Enum member must have an initializer");
        }

        if (empty($value)) {
            $member = EnumMemberToken::from($name);
        } else {
            $member = EnumMemberToken::from($name, $value[0]);
        }
        // TODO: Error if const & computed value
        $this->can_auto = ($member->getValue() instanceof NumberValue) || ($member->getValue() instanceof NoneValue);

        return $this->members[$member->getName()->getName()] = $member;
    }

    /**
     * @return array<string,EnumMemberToken>
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param array<array-key,string|EnumMemberToken> $members
     */
    public function setMembers(array $members): self
    {
        $this->members = [];
        foreach ($members as $member) {
            if ($member instanceof EnumMemberToken) {
                $this->addMember($member->getName(), $member->getValue());
            } else {
                $this->addMember($member);
            }
        }

        return $this;
    }

    public function isConst(): bool
    {
        return $this->const;
    }

    public function hasConst(bool $const = true): self
    {
        // TODO: Error if any computed values
        $this->const = $const;

        return $this;
    }
}
