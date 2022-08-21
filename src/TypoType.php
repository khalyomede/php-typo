<?php

declare(strict_types=1);

namespace PhpTypo\PhpTypo;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Const_;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\EnumCase;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;

enum TypoType: string
{
    case Unknown = "unknown type";
    case Variable = "variable";
    case Function = "function";
    case Class_ = "class";
    case Property = "property";
    case Method = "method";
    case Interface = "interface";
    case Enum = "enum";
    case EnumCase = "enum case";
    case ClassConstant = "class constant";
    case Constant = "constant";

    public static function getFromNode(Node $node): self
    {
        if ($node instanceof Variable) {
            return self::Variable;
        }

        if ($node instanceof Function_) {
            return self::Function;
        }

        if ($node instanceof Class_) {
            return self::Class_;
        }

        if ($node instanceof Property) {
            return self::Property;
        }

        if ($node instanceof ClassMethod) {
            return self::Method;
        }

        if ($node instanceof Interface_) {
            return self::Interface;
        }

        if ($node instanceof Enum_) {
            return self::Enum;
        }

        if ($node instanceof EnumCase) {
            return self::EnumCase;
        }

        if ($node instanceof ClassConst) {
            return self::ClassConstant;
        }

        if ($node instanceof Const_) {
            return self::Constant;
        }

        return self::Unknown;
    }
}
