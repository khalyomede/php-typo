<?php

declare(strict_types=1);

namespace Khalyomede\PhpTypo;

use Aminnairi\StringExtra\StringExtra;
use Exception;
use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Const_;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\EnumCase;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;
use PhpParser\NodeVisitorAbstract;

final class AstVisitor extends NodeVisitorAbstract
{
    public function enterNode(Node $node)
    {
        if (self::nodeShouldBeScanned($node)) {
            $words = self::getWordsFromNodeName($node);

            foreach ($words as $word) {
                if (!Words::exist($word)) {
                    Checker::$typos[] = new Typo(
                        self::getNodeName($node),
                        TypoType::getFromNode($node),
                        Checker::$currentFile,
                        $node->getStartLine(),
                        $word
                    );
                }
            }
        }

        return null;
    }

    private static function nodeShouldBeScanned(Node $node): bool
    {
        if (self::nodeIsVariable($node)) {
            return true;
        }
        if (self::nodeIsFunction($node)) {
            return true;
        }
        if (self::nodeIsMethod($node)) {
            return true;
        }
        if (self::nodeIsClass($node)) {
            return true;
        }
        if (self::nodeIsProperty($node)) {
            return true;
        }
        if (self::nodeIsInterface($node)) {
            return true;
        }
        if (self::nodeIsEnum($node)) {
            return true;
        }
        if (self::nodeIsEnumCase($node)) {
            return true;
        }
        if (self::nodeIsclassConstant($node)) {
            return true;
        }
        return self::nodeIsConst($node);
    }

    private static function nodeIsVariable(Node $node): bool
    {
        return $node instanceof Variable && is_string($node->name);
    }

    private static function nodeIsFunction(Node $node): bool
    {
        return $node instanceof Function_;
    }

    private static function nodeIsMethod(Node $node): bool
    {
        return $node instanceof ClassMethod;
    }

    private static function nodeIsClass(Node $node): bool
    {
        return $node instanceof Class_ && $node->name instanceof Identifier;
    }

    private static function nodeIsProperty(Node $node): bool
    {
        return $node instanceof Property;
    }

    private static function nodeIsInterface(Node $node): bool
    {
        return $node instanceof Interface_ && $node->name instanceof Identifier;
    }

    private static function nodeIsEnum(Node $node): bool
    {
        return $node instanceof Enum_ && $node->name instanceof Identifier;
    }

    private static function nodeIsEnumCase(Node $node): bool
    {
        return $node instanceof EnumCase;
    }

    private static function nodeIsclassConstant(Node $node): bool
    {
        return $node instanceof ClassConst;
    }

    private static function nodeIsConst(Node $node): bool
    {
        return $node instanceof Const_;
    }

    private static function getNodeName(Node $node): string
    {
        if (self::nodeIsVariable($node)) {
            /** @phpstan-ignore-next-line Access to an undefined property PhpParser\Node::$name. */
            return $node->name;
        }
        if (self::nodeIsFunction($node)) {
            /** @phpstan-ignore-next-line Access to an undefined property PhpParser\Node::$name. */
            return $node->name->name;
        }
        if (self::nodeIsMethod($node)) {
            /** @phpstan-ignore-next-line Access to an undefined property PhpParser\Node::$name. */
            return $node->name->name;
        }
        if (self::nodeIsClass($node)) {
            /** @phpstan-ignore-next-line Access to an undefined property PhpParser\Node::$name. */
            return $node->name->name;
        }
        if (self::nodeIsInterface($node)) {
            /** @phpstan-ignore-next-line Access to an undefined property PhpParser\Node::$name. */
            return $node->name->name;
        }
        if (self::nodeIsEnum($node)) {
            /** @phpstan-ignore-next-line Access to an undefined property PhpParser\Node::$name. */
            return $node->name->name;
        }
        if (self::nodeIsEnumCase($node)) {
            /** @phpstan-ignore-next-line Access to an undefined property PhpParser\Node::$name. */
            return $node->name->name;
        }

        if (self::nodeIsclassConstant($node) || self::nodeIsConst($node)) {
            /** @phpstan-ignore-next-line Access to an undefined property PhpParser\Node::$consts. */
            foreach ($node->consts as $const) {
                return $const->name->name;
            }

            throw new Exception("Name not found on const");
        }

        if (self::nodeIsProperty($node)) {
            /** @phpstan-ignore-next-line Access to an undefined property PhpParser\Node::$props. */
            foreach ($node->props as $prop) {
                return $prop->name->name;
            }

            throw new Exception("Name not found on property");
        }

        throw new Exception("Unsupported node type " . get_class($node));
    }

    /**
     * @return array<string>
     */
    private static function getWordsFromNodeName(Node $node): array
    {
        $nodeName = self::getNodeName($node);
        $words = explode(" ", StringExtra::toSentenceCase($nodeName));

        return Words::clean($words);
    }
}
