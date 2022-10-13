<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Converter;

use DQ5Studios\TypeScript\Generator\Attributes\IsAmbient;
use DQ5Studios\TypeScript\Generator\Attributes\IsClass;
use DQ5Studios\TypeScript\Generator\Attributes\IsComment;
use DQ5Studios\TypeScript\Generator\Attributes\IsConst;
use DQ5Studios\TypeScript\Generator\Attributes\IsEnum;
use DQ5Studios\TypeScript\Generator\Attributes\IsExport;
use DQ5Studios\TypeScript\Generator\Attributes\IsInterface;
use DQ5Studios\TypeScript\Generator\Attributes\IsName;
use DQ5Studios\TypeScript\Generator\Attributes\IsReadonly;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Tokens\VisibilityToken;
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
use ReflectionException;
use Roave\BetterReflection\Reflection\ReflectionAttribute;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionEnum;
use Roave\BetterReflection\Reflection\ReflectionNamedType;
use Roave\BetterReflection\Reflector\Exception\IdentifierNotFound;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpStanExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\Type as SymfonyType;

class Convert
{
    private function __construct(
        private NameToken $name,
        private NameToken|null $extends,
        /** @var NameToken[] */
        private array $implements = [],
        /** @var list<Member> */
        private array $members = [],
        /** @var ReflectionAttribute[] */
        private array $attributes = [],
    ) {
    }

    /**
     * @param class-string|object $class
     *
     * @throws InvalidArgumentException
     */
    public static function fromPHP(string|object $class): Type
    {
        try {
            if (\is_string($class)) {
                $reflection = ReflectionClass::createFromName($class);
            } elseif (!($class instanceof ReflectionClass)) {
                $reflection = ReflectionClass::createFromInstance($class);
            } else {
                $reflection = $class;
            }

            // If it's a native enum, reload as a reflection enum
            if ($reflection->isEnum()) {
                $reflection = ReflectionEnum::createFromName($reflection->getName());
            }
        } catch (ReflectionException|IdentifierNotFound) {
            throw new InvalidArgumentException("Class does not exist");
        }
        $class = $reflection->getName();

        $class_name = self::nameSafe($reflection->getName());
        $class_name = new NameToken($class_name);

        $extends = $reflection->getParentClass() ?: null;
        if (null !== $extends) {
            $extends = self::nameSafe($extends->getName());
            $extends = new NameToken($extends);
        }

        $implements = [];
        $interfaces = $reflection->getInterfaces();
        foreach ($interfaces as $interf) {
            $implements[] = new NameToken(self::nameSafe($interf->getName()));
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
            $p_name = new NameToken(self::nameSafe($prop->getName()));

            // Skip built ins
            if (
                $reflection->isEnum()
                && \in_array($p_name->getName(), ["name", "value"])
            ) {
                continue;
            }

            $type_detail = $info->getTypes($class, $prop->getName());
            if (null === $type_detail) {
                $type = new UnknownType();
            } else {
                $type = self::typeResolve($type_detail);
            }

            $visibility = match (true) {
                $prop->isPublic() => VisibilityToken::PUBLIC,
                $prop->isProtected() => VisibilityToken::PROTECTED,
                $prop->isPrivate() => VisibilityToken::PRIVATE,
            };

            $readonly = $prop->isReadOnly();
            $m = new Member(
                name: $p_name,
                type: $type,
                readonly: $readonly,
                visibility: $visibility,
            );

            // Check for IsReadonly attribute
            $attr_readonly = $prop->getAttributesByName(IsReadonly::class);
            if (!empty($attr_readonly)) {
                /** @psalm-suppress MixedArgument */
                $m->readonly = (new IsReadonly(...$attr_readonly[0]->getArguments()))->readonly;
            }

            if ($prop->hasDefaultValue()) {
                $m->value = $prop->getDefaultValue();
            }
            $type_comment = $info->getShortDescription($class, $prop->getName());
            if (!empty($type_comment)) {
                $m->comment = $type_comment;
            }
            $members[] = $m;
        }

        if ($reflection instanceof ReflectionEnum) {
            $backing_type = $reflection->isBacked() ? $reflection->getBackingType() : null;
            $cases = $reflection->getCases();
            foreach ($cases as $case) {
                $m = new Member(
                    name: new NameToken(self::nameSafe($case->getName())),
                    type: new UnknownType(),
                    readonly: true,
                    visibility: VisibilityToken::PUBLIC, // TODO: Look up visibility
                );

                if (is_a($backing_type, ReflectionNamedType::class)) {
                    $m->type = Type::from(Type::$php_type_map[$backing_type->getName()]);
                }
                if ($reflection->isBacked()) {
                    $m->value = $case->getValue();
                }

                $type_comment = $case->getDocComment();
                if (!empty($type_comment)) {
                    $m->comment = self::parseComment($type_comment);
                }
                $members[] = $m;
            }
        }

        $consts = $reflection->getConstants();
        foreach ($consts as $const) {
            /** @var mixed */
            $value = $const->getValue();
            $m = new Member(
                name: new NameToken(self::nameSafe($const->getName())),
                type: Type::from(Type::$php_type_map[\gettype($value)]),
                value: $value,
                readonly: true,
                visibility: VisibilityToken::PUBLIC, // TODO: Look up visibility
            );
            $type_comment = $const->getDocComment();
            if (!empty($type_comment)) {
                $m->comment = self::parseComment($type_comment);
            }
            $members[] = $m;
        }

        // echo $reflection->getDocComment();
        // print_r($info->getProperties($class));
        // $info->getTypes();

        $attributes = $reflection->getAttributes();

        $type = new self($class_name, $extends, $implements, $members, $attributes);

        foreach ($type->attributes as $attr) {
            if (IsEnum::class === $attr->getName()) {
                return $type->toEnum();
            }
            if (IsInterface::class === $attr->getName()) {
                return $type->toInterface();
            }
            if (IsClass::class === $attr->getName()) {
                return $type->toClass();
            }
        }

        if ($reflection->isEnum()) {
            return $type->toEnum();
        }

        if ($reflection->isInterface()) {
            return $type->toInterface();
        }

        return $type->toClass();
    }

    public function toClass(): ClassType
    {
        $class = new ClassType($this->name);
        if (null !== $this->extends) {
            $class->addExtend(new ClassType($this->extends));
        }
        // TODO: implement interfaces
        // if (!empty($this->implements)) {
        //     $class->addImplement(new ClassType($this->implements));
        // }
        foreach ($this->attributes as $attr) {
            /** @psalm-suppress MixedArgument */
            match ($attr->getName()) {
                IsName::class => $class->setName((new IsName(...$attr->getArguments()))->name),
                IsComment::class => $class->setComment((new IsComment(...$attr->getArguments()))->comment),
                IsExport::class => $class->hasExport((new IsExport(...$attr->getArguments()))->export),
                IsAmbient::class => $class->hasAmbient((new IsAmbient(...$attr->getArguments()))->ambient),
                default => null,
            };
            if (IsClass::class === $attr->getName()) {
                /** @psalm-suppress MixedArgument */
                $attr_def = new IsClass(...$attr->getArguments());
                if (isset($attr_def->name)) {
                    $class->setName($attr_def->name);
                }
                if (isset($attr_def->comment)) {
                    $class->setComment($attr_def->comment);
                }
                if (isset($attr_def->export)) {
                    $class->hasExport($attr_def->export);
                }
                if (isset($attr_def->ambient)) {
                    $class->hasAmbient($attr_def->ambient);
                }
            }
        }

        foreach ($this->members as $prop) {
            if (!isset($prop->value) || $class->isExport() || $class->isAmbient()) {
                $prop->value = new NoneValue();
            }
            $p = $class->addProperty($prop->name, $prop->type, $prop->value);
            if (isset($prop->comment)) {
                $p->addComment($prop->comment);
            }
            if (isset($prop->readonly)) {
                $p->hasReadonly($prop->readonly);
            }
            $p->setVisibility($prop->visibility);
        }

        return $class;
    }

    public function toInterface(): InterfaceType
    {
        $interface = new InterfaceType($this->name);
        if (null !== $this->extends) {
            $interface->addExtend(new InterfaceType($this->extends));
        }
        foreach ($this->members as $prop) {
            $p = $interface->addProperty($prop->name, $prop->type);
            if (isset($prop->comment)) {
                $p->addComment($prop->comment);
            }
            if (isset($prop->readonly)) {
                $p->hasReadonly($prop->readonly);
            }
        }
        foreach ($this->attributes as $attr) {
            /** @psalm-suppress MixedArgument */
            match ($attr->getName()) {
                IsName::class => $interface->setName((new IsName(...$attr->getArguments()))->name),
                IsComment::class => $interface->setComment((new IsComment(...$attr->getArguments()))->comment),
                IsExport::class => $interface->hasExport((new IsExport(...$attr->getArguments()))->export),
                IsAmbient::class => $interface->hasAmbient((new IsAmbient(...$attr->getArguments()))->ambient),
                default => null,
            };
            if (IsInterface::class === $attr->getName()) {
                /** @psalm-suppress MixedArgument */
                $attr_def = new IsInterface(...$attr->getArguments());
                if (isset($attr_def->name)) {
                    $interface->setName($attr_def->name);
                }
                if (isset($attr_def->comment)) {
                    $interface->setComment($attr_def->comment);
                }
                if (isset($attr_def->export)) {
                    $interface->hasExport($attr_def->export);
                }
                if (isset($attr_def->ambient)) {
                    $interface->hasAmbient($attr_def->ambient);
                }
            }
        }

        return $interface;
    }

    public function toEnum(): EnumType
    {
        $enum = new EnumType($this->name);
        foreach ($this->members as $prop) {
            if (isset($prop->value) && (is_numeric($prop->value) || \is_string($prop->value))) {
                $p = $enum->addMember($prop->name, $prop->value);
            } else {
                $p = $enum->addMember($prop->name);
            }
            if (isset($prop->comment)) {
                $p->addComment($prop->comment);
            }
        }
        foreach ($this->attributes as $attr) {
            /** @psalm-suppress MixedArgument */
            match ($attr->getName()) {
                IsName::class => $enum->setName((new IsName(...$attr->getArguments()))->name),
                IsComment::class => $enum->setComment((new IsComment(...$attr->getArguments()))->comment),
                IsExport::class => $enum->hasExport((new IsExport(...$attr->getArguments()))->export),
                IsAmbient::class => $enum->hasAmbient((new IsAmbient(...$attr->getArguments()))->ambient),
                IsConst::class => $enum->hasConst((new IsConst(...$attr->getArguments()))->const),
                default => null,
            };
            if (IsEnum::class === $attr->getName()) {
                /** @psalm-suppress MixedArgument */
                $attr_def = new IsEnum(...$attr->getArguments());
                if (isset($attr_def->name)) {
                    $enum->setName($attr_def->name);
                }
                if (isset($attr_def->comment)) {
                    $enum->setComment($attr_def->comment);
                }
                if (isset($attr_def->export)) {
                    $enum->hasExport($attr_def->export);
                }
                if (isset($attr_def->ambient)) {
                    $enum->hasAmbient($attr_def->ambient);
                }
                if (isset($attr_def->const)) {
                    $enum->hasConst($attr_def->const);
                }
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

        return trim($comment);
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
            if (!$type->isCollection() && "object" !== $basic && \array_key_exists($basic, Type::$php_type_map)) {
                $type_list[] = Type::from(Type::$php_type_map[$basic]);
                continue;
            }
            if ("object" === $basic) {
                $class = $type->getClassName();
                if (null === $class) {
                    $type_list[] = new ObjectType();
                    continue;
                }
                $class = explode("\\", $class);
                $class = array_pop($class);
                try {
                    $class_type = Type::from($class);
                } catch (InvalidArgumentException) {
                    $class_type = (new class () extends Type {
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
                    $typed_key = self::typeResolve($key);
                }
                $value = $type->getCollectionValueTypes();
                $typed_value = new UnknownType();
                if (!empty($value)) {
                    $typed_value = self::typeResolve($value);
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
            if (1 === \count($type_list)) {
                return $type_list[0];
            }

            return UnionType::of(...$type_list);
        }

        return new UnknownType();
    }
}
