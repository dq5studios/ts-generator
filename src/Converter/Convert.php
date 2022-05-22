<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Converter;

use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Types\ArrayType;
use DQ5Studios\TypeScript\Generator\Types\ClassType;
use DQ5Studios\TypeScript\Generator\Types\EnumType;
use DQ5Studios\TypeScript\Generator\Types\InterfaceType;
use DQ5Studios\TypeScript\Generator\Types\NullType;
use DQ5Studios\TypeScript\Generator\Types\NumberType;
use DQ5Studios\TypeScript\Generator\Types\ObjectType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Types\UnionType;
use DQ5Studios\TypeScript\Generator\Types\UnknownType;
use DQ5Studios\TypeScript\Generator\Types\VoidType;
use DQ5Studios\TypeScript\Generator\Values\NoneValue;
use InvalidArgumentException;
use ReflectionAttribute;
use ReflectionClass;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpStanExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\Type as SymfonyType;

/**
 * @psalm-type memberParts = array{
 *      name:NameToken,
 *      type:Type,
 *      value?:mixed,
 *      comment?:string,
 *      readonly?:boolean
 * }
 */

class Convert
{
    final private function __construct(
        private NameToken $name,
        private NameToken | null $extends,
        /** @var NameToken[] */
        private array $implements = [],
        /** @var list<memberParts> */
        private array $members = [],
        /** @var ReflectionAttribute[] */
        private array $attributes = [],
    ) {
    }

    /**
     * @param class-string|object $class
     * @throws InvalidArgumentException
     */
    public static function fromPHP(string | object $class): Type
    {
        if (is_string($class)) {
            if (!class_exists($class)) {
                throw new InvalidArgumentException("Class does not exist");
            }
        } else {
            $class = get_class($class);
        }

        $reflection = new ReflectionClass($class);
        $class_name = Convert::nameSafe($reflection->getName());
        $class_name = new NameToken($class_name);

        $extends = $reflection->getParentClass() ?: null;
        if ($extends) {
            $extends = Convert::nameSafe($extends->getName());
            $extends = new NameToken($extends);
        }

        $implements = [];
        $interfaces = $reflection->getInterfaces();
        foreach ($interfaces as $interf) {
            $implements[] = new NameToken(Convert::nameSafe($interf->getName()));
        }

        $php_doc = new PhpDocExtractor();
        $ref = new ReflectionExtractor();
        // $stan = new PhpStanExtractor();
        $info = new PropertyInfoExtractor(
            [$ref],
            [$php_doc, $ref],
            [$php_doc],
            [$ref],
            [$ref],
        );

        $members = [];
        $props = $reflection->getProperties();
        foreach ($props as $prop) {
            $p_name = new NameToken(Convert::nameSafe($prop->getName()));
            $type_detail = $info->getTypes($class, $prop->getName());
            if (is_null($type_detail)) {
                $type = new UnknownType();
            } else {
                $type = Convert::typeResolve($type_detail);
            }
            $m = [
                "name" => $p_name,
                "type" => $type,
            ];
            if ($prop->hasDefaultValue()) {
                /** @var mixed */
                $m["value"] = $prop->getDefaultValue();
            }
            $type_comment = $info->getShortDescription($class, $prop->getName());
            if (!empty($type_comment)) {
                $m["comment"] = $type_comment;
            }
            $members[] = $m;
        }
        $consts = $reflection->getReflectionConstants();
        foreach ($consts as $const) {
            $value = $const->getValue();
            $m = [
                "name" => new NameToken(Convert::nameSafe($const->getName())),
                "type" => Type::from(Type::$php_type_map[gettype($value)]),
                "value" => $value,
                "readonly" => true,
            ];
            $type_comment = $const->getDocComment();
            if (!empty($type_comment)) {
                $m["comment"] = Convert::parseComment($type_comment);
            }
            $members[] = $m;
        }

        // echo $reflection->getDocComment();
        // print_r($info->getProperties($class));
        // $info->getTypes();

        $attributes = $reflection->getAttributes();

        $type = new self($class_name, $extends, $implements, $members, $attributes);

        foreach ($type->attributes as $attr) {
            if (EnumType::class === $attr->getName()) {
                return $type->toEnum();
            }
            if (InterfaceType::class === $attr->getName()) {
                return $type->toInterface();
            }
            if (ClassType::class === $attr->getName()) {
                return $type->toClass();
            }
        }
        return $type->toClass();
    }

    public function toClass(): ClassType
    {
        $class = new ClassType($this->name);
        if (!is_null($this->extends)) {
            $class->addExtend(new ClassType($this->extends));
        }
        // TODO: implement interfaces
        // if (!empty($this->implements)) {
        //     $class->addImplement(new ClassType($this->implements));
        // }
        foreach ($this->members as $prop) {
            if (!isset($prop["value"])) {
                $prop["value"] = new NoneValue();
            }
            $p = $class->addProperty($prop["name"], $prop["type"], $prop["value"]);
            if (isset($prop["comment"])) {
                $p->addComment($prop["comment"]);
            }
            if (isset($prop["readonly"])) {
                $p->hasReadonly($prop["readonly"]);
            }
        }
        return $class;
    }

    public function toInterface(): InterfaceType
    {
        $interface = new InterfaceType($this->name);
        if (!is_null($this->extends)) {
            $interface->addExtend(new InterfaceType($this->extends));
        }
        foreach ($this->members as $prop) {
            $p = $interface->addProperty($prop["name"], $prop["type"]);
            if (isset($prop["comment"])) {
                $p->addComment($prop["comment"]);
            }
            if (isset($prop["readonly"])) {
                $p->hasReadonly($prop["readonly"]);
            }
        }
        return $interface;
    }

    public function toEnum(): EnumType
    {
        $enum = new EnumType($this->name);
        foreach ($this->members as $prop) {
            if (isset($prop["value"]) && (is_numeric($prop["value"]) || is_string($prop["value"]))) {
                $p = $enum->addMember($prop["name"], $prop["value"]);
            } else {
                $p = $enum->addMember($prop["name"]);
            }
            if (isset($prop["comment"])) {
                $p->addComment($prop["comment"]);
            }
        }
        return $enum;
    }

    private static function nameSafe(string $name): string
    {
        $parts = explode("\\", $name);
        $name = array_pop($parts);
        $name = preg_replace("/[^a-zA-Z0-9_\x80-\xff]/", "_", $name);
        $name[0] = preg_replace("/[^a-zA-Z_\x80-\xff]/", "_", $name[0]);
        return $name;
    }

    private static function parseComment(string $comment): string
    {
        $comment = preg_replace("/\/\*\*(.*)\*\//ms", "$1", $comment);
        $comment = str_replace([" *", "\n"], "", $comment);
        $comment = trim($comment);
        return $comment;
    }

    /**
     * @param array<array-key,SymfonyType> $details
     */
    private static function typeResolve(array $details): Type
    {
        if (empty($details)) {
            return new VoidType();
        }
        $type_list = [];
        foreach ($details as $type) {
            if ($type->isNullable()) {
                $type_list[] = new NullType();
            }
            $basic = $type->getBuiltinType();
            if (!$type->isCollection() && $basic !== "object") {
                if (array_key_exists($basic, Type::$php_type_map)) {
                    $type_list[] = Type::from(Type::$php_type_map[$basic]);
                    continue;
                }
            }
            if ($basic === "object") {
                $class = $type->getClassName();
                if (is_null($class)) {
                    $type_list[] = new ObjectType();
                    continue;
                }
                $class = explode("\\", $class);
                $class = array_pop($class);
                try {
                    /** @psalm-suppress ArgumentTypeCoercion We're not sure if it's a valid type */
                    $class_type = Type::from($class);
                } catch (InvalidArgumentException) {
                    $class_type = (new class extends Type {
                        protected string $type = "";
                    })->setType($class);
                }
                $type_list[] = $class_type;
                continue;
            }
            if ($type->isCollection()) {
                $key = $type->getCollectionKeyTypes();
                $typed_key = Type::from(Type::NUMBER);
                if (!empty($key)) {
                    $typed_key = Convert::typeResolve($key);
                }
                $value = $type->getCollectionValueTypes();
                $typed_value = new UnknownType();
                if (!empty($value)) {
                    $typed_value = Convert::typeResolve($value);
                }
                if ($typed_key instanceof NumberType) {
                    $type_list[] = ArrayType::of($typed_value);
                    continue;
                }
                $obj = new ObjectType();
                $obj->addIndexSignature(Type::STRING, $typed_value);
                $type_list[] = $obj;
                continue;
            }
            $type_list[] = new UnknownType();
        }
        if (!empty($type_list)) {
            if (1 === count($type_list)) {
                return $type_list[0];
            }
            return UnionType::of(...$type_list);
        }
        return new UnknownType();
    }
}
