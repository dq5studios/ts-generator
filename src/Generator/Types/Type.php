<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types;

use InvalidArgumentException;

use function array_key_exists;

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
        self::ANY => AnyType::class,
        self::ARRAY => ArrayType::class,
        self::BIGINT => BigIntType::class,
        self::BOOLEAN => BooleanType::class,
        self::FUNCTION => FunctionType::class,
        self::INTERSECTION => IntersectionType::class,
        self::NEVER => NeverType::class,
        self::NULL => NullType::class,
        self::NUMBER => NumberType::class,
        self::OBJECT => ObjectType::class,
        self::STRING => StringType::class,
        self::SYMBOL => SymbolType::class,
        self::TUPLE => TupleType::class,
        self::UNDEFINED => UndefinedType::class,
        self::UNION => UnionType::class,
        self::UNKNOWN => UnknownType::class,
        self::VOID => VoidType::class,
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
    public static function from(string|Type $type): self
    {
        if ($type instanceof self) {
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
        if (array_key_exists($type, self::$php_type_map)) {
            $type = self::$php_type_map[$type];
        }
        if (array_key_exists($type, self::$type_map)) {
            $type = self::$type_map[$type];
        }
        if (!is_subclass_of($type, self::class)) {
            throw new InvalidArgumentException("Unknown type");
        }

        return new $type();
    }
}
