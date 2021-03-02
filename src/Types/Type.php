<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use InvalidArgumentException;

/**
 * This is a type
 *
 * @psalm-consistent-constructor
 */
abstract class Type
{
    public const ANY = "any";
    public const ARRAY = "array";
    public const BIGINT = "bigint";
    public const BOOLEAN = "boolean";
    public const FUNCTION = "function";
    public const INTERSECTION = "intersection";
    public const NEVER = "never";
    public const NULL = "null";
    public const NUMBER = "number";
    public const STRING = "string";
    public const SYMBOL = "symbol";
    public const TUPLE = "tuple";
    public const UNDEFINED = "undefined";
    public const UNION = "union";
    public const UNKNOWN = "unknown";
    public const VOID = "void";

    /** @var array<Type::*,class-string<Type>> */
    private static array $class_map = [
        Type::ANY => AnyType::class,
        Type::ARRAY => ArrayType::class,
        Type::BIGINT => BigIntType::class,
        Type::BOOLEAN => BooleanType::class,
        Type::FUNCTION => FunctionType::class,
        Type::INTERSECTION => IntersectionType::class,
        Type::NEVER => NeverType::class,
        Type::NULL => NullType::class,
        Type::NUMBER => NumberType::class,
        Type::STRING => StringType::class,
        Type::SYMBOL => SymbolType::class,
        Type::TUPLE => TupleType::class,
        Type::UNDEFINED => UndefinedType::class,
        Type::UNION => UnionType::class,
        Type::UNKNOWN => UnknownType::class,
        Type::VOID => VoidType::class,
    ];

    protected string $type;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param class-string<Type>|Type|Type::* $type
     */
    public static function from(string | Type $type): Type
    {
        if ($type instanceof Type) {
            return $type;
        }
        if (strpos($type, "|") !== false) {
            /** @var list<Type> */
            $parts = explode("|", $type);
            return UnionType::of(...$parts);
        }
        if (strpos($type, "&") !== false) {
            /** @var list<Type> */
            $parts = explode("&", $type);
            return IntersectionType::of(...$parts);
        }
        if (array_key_exists($type, self::$class_map)) {
            $type = self::$class_map[$type];
        }
        if (!is_subclass_of($type, Type::class)) {
            throw new InvalidArgumentException();
        }
        return new $type();
    }

    public function __toString(): string
    {
        return $this->type;
    }
}
