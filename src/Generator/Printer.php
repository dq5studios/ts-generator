<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator;

use DQ5Studios\TypeScript\Generator\Tokens\CommentToken;
use DQ5Studios\TypeScript\Generator\Tokens\FunctionSignatureToken;
use DQ5Studios\TypeScript\Generator\Tokens\IndexSignatureToken;
use DQ5Studios\TypeScript\Generator\Tokens\MemberToken;
use DQ5Studios\TypeScript\Generator\Tokens\NameToken;
use DQ5Studios\TypeScript\Generator\Tokens\VisibilityToken;
use DQ5Studios\TypeScript\Generator\Types\ArrayType;
use DQ5Studios\TypeScript\Generator\Types\ClassType;
use DQ5Studios\TypeScript\Generator\Types\EnumType;
use DQ5Studios\TypeScript\Generator\Types\FunctionType;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanName;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanOptional;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanReadonly;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanSpread;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanStatic;
use DQ5Studios\TypeScript\Generator\Types\Interfaces\CanVisibility;
use DQ5Studios\TypeScript\Generator\Types\InterfaceType;
use DQ5Studios\TypeScript\Generator\Types\MultiType;
use DQ5Studios\TypeScript\Generator\Types\NoneType;
use DQ5Studios\TypeScript\Generator\Types\ObjectType;
use DQ5Studios\TypeScript\Generator\Types\TupleType;
use DQ5Studios\TypeScript\Generator\Types\Type;
use DQ5Studios\TypeScript\Generator\Values\ArrayValue;
use DQ5Studios\TypeScript\Generator\Values\BooleanValue;
use DQ5Studios\TypeScript\Generator\Values\NoneValue;
use DQ5Studios\TypeScript\Generator\Values\NullValue;
use DQ5Studios\TypeScript\Generator\Values\NumberValue;
use DQ5Studios\TypeScript\Generator\Values\ObjectValue;
use DQ5Studios\TypeScript\Generator\Values\StringValue;
use DQ5Studios\TypeScript\Generator\Values\UndefinedValue;
use DQ5Studios\TypeScript\Generator\Values\Value;

class Printer
{
    protected string $indent = "    ";
    protected string $open_bracket = " {";
    protected string $close_bracket = "}";
    protected string $member_sep = ";";
    protected string $property_sep = ",";

    public function printArray(ArrayType $array): string
    {
        $callback = [$this, "printType"];
        /** @var list<string> */
        $is = array_map($callback, $array->getContents());
        $types = match (count($is)) {
            0 => "",
            1 => $is[0],
            default => "(" . join($array->getSeperator(), $is) . ")",
        };

        return $types . "[]";
    }

    public function printArrayValue(ArrayValue $array): string
    {
        $callback = [$this, "printValue"];
        /** @var list<string> */
        $is = array_map($callback, $array->getValue());
        if (empty($is)) {
            return "[]";
        }

        return "[" . implode(", ", $is) . "]";
    }

    public function printClass(ClassType $class): string
    {
        $comment = $this->printComment($class->getComment());
        $output = !empty($comment) ? "{$comment}\n" : "";
        $output .= $class->isExport() ? "export " : "";
        $output .= $class->isAmbient() ? "declare " : "";
        $output .= "class " . $this->printName($class->getName());
        if (!empty($class->getExtend())) {
            $extends = array_map(
                fn (CanName $type): string => $this->printName($type->getName()),
                $class->getExtend(),
            );
            $output .= " extends " . implode(", ", $extends);
        }
        if (!empty($class->getImplement())) {
            $implements = array_map(
                fn (CanName $type): string => $this->printName($type->getName()),
                $class->getImplement(),
            );
            $output .= " implements " . implode(", ", $implements);
        }
        $output .= $this->open_bracket . "\n";
        foreach ($class->getProperties() as $prop) {
            $output .= preg_replace("/^(.*)$/m", $this->indent . "$1", $this->printMemberToken($prop));
            $output .= $this->member_sep . "\n";
        }
        $output .= $this->close_bracket;

        return $output;
    }

    public function printComment(CommentToken $comment): string
    {
        $comments = trim($comment->get());
        if (empty($comments)) {
            return "";
        }
        if (empty(preg_match_all("/\n/", $comments))) {
            return "/** {$comments} */";
        }
        $comments = preg_replace("/\n/", "\n * ", $comments);
        $comments = preg_replace('/^ \* $/m', ' *', $comments);

        return "/**\n * {$comments}\n */";
    }

    public function printEnum(EnumType $enum): string
    {
        $comment = $this->printComment($enum->getComment());
        $output = !empty($comment) ? "{$comment}\n" : "";
        $output .= $enum->isExport() ? "export " : "";
        $output .= $enum->isAmbient() ? "declare " : "";
        $output .= $enum->isConst() ? "const " : "";
        $output .= "enum " . $this->printName($enum->getName()) . $this->open_bracket . "\n";
        foreach ($enum->getMembers() as $value) {
            $output .= preg_replace("/^(.*)$/m", $this->indent . "$1", $this->printMemberToken($value));
            $output .= $this->property_sep . "\n";
        }
        $output .= $this->close_bracket;

        return $output;
    }

    public function printFile(File $file): string
    {
        $output = "";
        foreach ($file->getContents() as $type) {
            $output .= $this->printType($type) . "\n\n";
        }

        return $output;
    }

    public function printFunction(FunctionType $func): string
    {
        if (!$func->isSignature()) {
            return $func->getType();
        }

        $callback = [$this, "printMemberToken"];
        /** @var array<string,string> */
        $param = array_map($callback, $func->getParameters());
        $param = $this->shakeParameterThis($param);

        $output = "(" . implode(", ", $param) . ")";
        $output .= " => ";
        $output .= $this->printType($func->getReturn());

        return $output;
    }

    public function printFunctionSignature(FunctionSignatureToken $func): string
    {
        $output = ($func->isConstructor() ? "new " : "");
        $output .= ($func->isMethod() ? $func->getName() : "");
        // $output .= ((!$func->isCallable() && !$func->isConstructor() && $func->getName()) ? $func->getName() : "");
        $callback = [$this, "printMemberToken"];
        /** @var array<string,string> */
        $param = array_map($callback, $func->getParameters());
        $param = $this->shakeParameterThis($param);

        $output .= "(" . implode(", ", $param) . ")";

        return $output;
    }

    public function printIndexSignature(IndexSignatureToken $index): string
    {
        return "[{$index->getName()}: " . $this->printType($index->getType()) . "]";
    }

    public function printInterface(InterfaceType $interface): string
    {
        $comment = $this->printComment($interface->getComment());
        $output = !empty($comment) ? "{$comment}\n" : "";
        $output .= $interface->isExport() ? "export " : "";
        $output .= $interface->isAmbient() ? "declare " : "";
        $output .= "interface " . $this->printName($interface->getName());
        if (!empty($interface->getExtend())) {
            $extends = array_map(
                fn (CanName $type): string => $this->printName($type->getName()),
                $interface->getExtend(),
            );
            $output .= " extends " . implode(", ", $extends);
        }
        $output .= $this->open_bracket . "\n";
        foreach ($interface->getProperties() as $prop) {
            $output .= preg_replace("/^(.*)$/m", $this->indent . "$1", $this->printMemberToken($prop));
            $output .= $this->member_sep . "\n";
        }
        $output .= $this->close_bracket;

        return $output;
    }

    public function printMemberToken(MemberToken $token): string
    {
        $comment = $this->printComment($token->getComment());
        $output = !empty($comment) ? "{$comment}\n" : "";
        $value = $token->getValue();
        $type = $token->getType();
        if ($token instanceof CanReadonly) {
            // TODO: Check type is not fn
            // TODO: Confirm class/interface
            if ($token->isReadonly()) {
                $output .= "readonly ";
            }
        }
        if ($token instanceof CanStatic) {
            // TODO: Confirm class/interface
            if ($token->isStatic()) {
                $output .= "static ";
            }
        }
        if ($token instanceof CanVisibility) {
            if ($visibility = $token->getVisibility()) {
                $output .= match ($visibility->get()) {
                    VisibilityToken::PUBLIC => "public ",
                    VisibilityToken::PROTECTED => "protected ",
                    VisibilityToken::PRIVATE => "private ",
                };
            }
        }
        if ($value instanceof NoneValue) {
            if ($token instanceof CanSpread && $token->isSpread()) {
                $output .= "...";
            }
        }
        $output .= $this->printName($token->getName());
        if ($value instanceof NoneValue) {
            if ($token instanceof CanOptional && $token->isOptional()) {
                $output .= "?";
            }
        }
        if (!($type instanceof NoneType)) {
            $output .= ": ";
            if ($type instanceof CanName) {
                $output .= $this->printName($type->getName());
            } else {
                $output .= $this->printType($type);
            }
        }
        if (!($value instanceof NoneValue)) {
            $output .= " = " . (string) $token->getValue();
        }

        return $output;
    }

    public function printMultiType(MultiType $type): string
    {
        $callback = [$this, "printType"];
        $output = "";
        $types = $type->getContents();
        if (count($types) > 1) {
            $output .= "(";
        }
        /** @var list<string> */
        $mapping = array_map($callback, $type->getContents());
        $output .= join($type->getSeperator(), $mapping);
        if (count($types) > 1) {
            $output .= ")";
        }

        return $output;
    }

    public function printName(NameToken $name): string
    {
        switch (true) {
            case $name instanceof IndexSignatureToken:
                return $this->printIndexSignature($name);
            case $name instanceof FunctionSignatureToken:
                return $this->printFunctionSignature($name);
        }

        return $name->getName();
    }

    public function printObject(ObjectType $object): string
    {
        $comment = $this->printComment($object->getComment());
        $output = !empty($comment) ? "{$comment}\n" : "";
        if (empty($object->getProperties())) {
            return $output . $object->getType();
        }
        if (1 === count($object->getProperties())) {
            return $this->printObjectSingleLine($object);
        }
        $output .= "{\n";
        foreach ($object->getProperties() as $prop) {
            $output .= preg_replace("/^(.*)$/m", $this->indent . "$1", $this->printMemberToken($prop));
            $output .= $this->property_sep . "\n";
        }
        $output .= $this->close_bracket;

        return $output;
    }

    public function printObjectSingleLine(ObjectType $object): string
    {
        if (empty($object->getProperties())) {
            return $object->getType();
        }
        $output = trim($this->open_bracket) . " ";
        foreach ($object->getProperties() as $prop) {
            $output .= preg_replace("/^(.*)$/m", "$1", $this->printMemberToken($prop));
            $output .= $this->property_sep . " ";
        }
        $output = rtrim($output, $this->property_sep . " ");
        $output .= " " . $this->close_bracket;

        return $output;
    }

    public function printObjectValue(ObjectValue $object): string
    {
        $callback = [$this, "printValue"];
        /** @var list<string> */
        $is = array_map($callback, $object->getValue());
        if (empty($is)) {
            return "{}";
        }
        $contents = "";
        foreach ($is as $key => $value) {
            $contents .= "\"{$key}\": {$value}, ";
        }
        $contents = rtrim($contents, " ,");

        return "{ {$contents} }";
    }

    public function printTuple(TupleType $tuple): string
    {
        $callback = [$this, "printType"];
        /** @var list<string> */
        $mapping = array_map($callback, $tuple->getContents());

        return "[" . join($tuple->getSeperator(), $mapping) . "]";
    }

    public function printType(Type $type): string
    {
        switch (true) {
            case $type instanceof ArrayType:
                return $this->printArray($type);
            case $type instanceof ClassType:
                return $this->printClass($type);
            case $type instanceof EnumType:
                return $this->printEnum($type);
            case $type instanceof FunctionType:
                return $this->printFunction($type);
            case $type instanceof InterfaceType:
                return $this->printInterface($type);
            case $type instanceof ObjectType:
                return $this->printObject($type);
            case $type instanceof TupleType:
                return $this->printTuple($type);
            case $type instanceof MultiType:
                return $this->printMultiType($type);
        }

        return $type->getType();
    }

    public function printValue(Value $value): string
    {
        switch (true) {
            case $value instanceof ArrayValue:
                return $this->printArrayValue($value);
            case $value instanceof BooleanValue:
                return $value->getValue() ? "true" : "false";
            case $value instanceof NoneValue:
                return "";
            case $value instanceof NullValue:
                return "null";
            case $value instanceof NumberValue:
                return (string) $value->getValue();
            case $value instanceof ObjectValue:
                return $this->printObjectValue($value);
            case $value instanceof StringValue:
                return "\"{$value->getValue()}\"";
            case $value instanceof UndefinedValue:
                return "undefined";
        }

        return (string) $value;
    }

    /**
     * Shake the "this" parameter to the front of the list
     *
     * @param array<string,string> $list
     *
     * @return array<array-key,string>
     */
    private function shakeParameterThis(array $list): array
    {
        if (array_key_exists("this", $list)) {
            $move = $list["this"];
            unset($list["this"]);
            array_unshift($list, $move);
        }

        return $list;
    }
}
