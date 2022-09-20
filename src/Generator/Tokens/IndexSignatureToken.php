<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

use DQ5Studios\TypeScript\Generator\Types\NumberType;
use DQ5Studios\TypeScript\Generator\Types\StringType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Types\VoidType;
use InvalidArgumentException;

/**
 * An index signaure
 */
class IndexSignatureToken extends NameToken
{
    protected Type|null $type = null;

    /**
     * @param class-string<Type>|Type|Type::* $type
     */
    public static function of(string|Type $type): self
    {
        return (new self("index"))->setType($type);
    }

    public function getType(): Type
    {
        return $this->type ?? (new VoidType());
    }

    /**
     * @param class-string<Type>|Type|Type::* $type
     */
    public function setType(string|Type $type): self
    {
        $type = Type::from($type);
        if (!($type instanceof NumberType) && !($type instanceof StringType)) {
            throw new InvalidArgumentException("Only string and number types can be index types");
        }
        $this->type = $type;

        return $this;
    }
}
