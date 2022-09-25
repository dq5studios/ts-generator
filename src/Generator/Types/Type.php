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
    public const OBJECT = "object";
    public const STRING = "string";
    public const SYMBOL = "symbol";
    public const TUPLE = "tuple";
    public const UNDEFINED = "undefined";
    public const UNION = "union";
    public const UNKNOWN = "unknown";
    public const VOID = "void";

    /** @var array<string,class-string<Type>> */
    public static array $php_type_map = [
        "array" => ArrayType::class,
        "boolean" => BooleanType::class,
        "bool" => BooleanType::class,
        "double" => NumberType::class,
        "float" => NumberType::class,
        "int" => NumberType::class,
        "integer" => NumberType::class,
        "string" => StringType::class,
        "object" => ObjectType::class,
    ];

    /** @var array<Type::*,class-string<Type>> */
    private static array $type_map = [
        Type::ANY => AnyType::class,
        Type::ARRAY => ArrayType::class,
        Type::BIGINT => BigIntType::class,
        Type::BOOLEAN => BooleanType::class,
        Type::FUNCTION => FunctionType::class,
        Type::INTERSECTION => IntersectionType::class,
        Type::NEVER => NeverType::class,
        Type::NULL => NullType::class,
        Type::NUMBER => NumberType::class,
        Type::OBJECT => ObjectType::class,
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
     * @param class-string<Type>|Type|Type::*|string $type
     */
    public static function from(string|Type $type): Type
    {
        if ($type instanceof Type) {
            return $type;
        }
        if (str_ends_with($type, "[]")) {
            $type = trim($type, "[]()");
            /** @var list<Type> */
            $parts = explode("|", $type);

            return ArrayType::of(...$parts);
        }
        if (str_contains($type, "|")) {
            $type = trim($type, "()");
            /** @var list<Type> */
            $parts = explode("|", $type);

            return UnionType::of(...$parts);
        }
        if (str_contains($type, "&")) {
            $type = trim($type, "()");
            /** @var list<Type> */
            $parts = explode("&", $type);

            return IntersectionType::of(...$parts);
        }
        // Check if they passed in a PHP type by mistake
        if (array_key_exists($type, Type::$php_type_map)) {
            $type = Type::$php_type_map[$type];
        }
        if (array_key_exists($type, Type::$type_map)) {
            $type = Type::$type_map[$type];
        }
        if (!is_subclass_of($type, Type::class)) {
            throw new InvalidArgumentException("Unknown type");
        }

        return new $type();
    }
}
