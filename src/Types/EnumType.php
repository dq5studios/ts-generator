<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use DQ5Studios\TypeScript\Generator\Tokens\EnumMemberToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Values\NoneValue;
use DQ5Studios\TypeScript\Generator\Values\NumberValue;
use DQ5Studios\TypeScript\Generator\Values\Value;
use InvalidArgumentException;

/**
 * The enum type, equivalent to PHP enum
 */
class EnumType extends ContainerType
{
    protected string $type = "enum";
    protected NameToken $name;
    /** @var list<EnumMemberToken> */
    protected array $members = [];
    protected bool $const = false;
    protected bool $export = false;
    protected bool $ambient = false;

    private bool $can_auto = true;

    /**
     * @throws InvalidArgumentException
     */
    public function addMember(string | NameToken $name, int | float | string | Value ...$value): self
    {
        if (count($value) > 1) {
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
        $this->members[] = $member;
        $this->can_auto = ($member->getValue() instanceof NumberValue) || ($member->getValue() instanceof NoneValue);

        return $this;
    }

    /**
     * @return list<EnumMemberToken>
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param list<EnumMemberToken> $members
     */
    public function setMembers(array $members): self
    {
        $this->members = [];
        foreach ($members as $member) {
            /** @psalm-suppress DocblockTypeContradiction */
            if (!($member instanceof EnumMemberToken)) {
                throw new InvalidArgumentException();
            }
            $this->addMember($member->getName(), $member->getValue());
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
        // TODO: If any members computed throw error on enable
        $this->ambient = $enable;
        return $this;
    }

    public function isConst(): bool
    {
        return $this->const;
    }

    public function setConst(bool $const = true): self
    {
        // TODO: Error if any computed values
        $this->const = $const;
        return $this;
    }

    public function __toString(): string
    {
        $output = ($this->const) ? "const " : "";
        $output .= "enum {$this->name} {\n";
        foreach ($this->members as $value) {
            $output .= "    {$value},\n";
        }
        $output .= "}";
        return $output;
    }
}
