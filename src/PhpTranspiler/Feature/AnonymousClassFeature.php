<?php
declare (strict_types = 1);

namespace JanPiet\PhpTranspiler\Feature;

use JanPiet\PhpTranspiler\NodeSearch;
use JanPiet\PhpTranspiler\ParentNotFoundException;
use PhpParser\Node;

class AnonymousClassFeature implements FeatureInterface
{
    private $cnt = 0;

    /**
     * @param NodeSearch $nodeSearch
     * @return bool
     */
    public function fix(NodeSearch $nodeSearch): bool
    {
        $found = false;
        foreach ($nodeSearch->eachType(Node\Expr\New_::class) as $node) {
            if (!$node->class instanceof Node\Stmt\Class_) {
                continue;
            }
            $found = true;

            $className = 'mine' . sha1((string) $this->cnt++);
            $statementName = $className;

            $newClass = $node->class;
            $newClass->name = $className;

            try {
                /** @var Node\Stmt\Namespace_ $namespaceNode */
                $namespaceNode = $nodeSearch->findParent(Node\Stmt\Namespace_::class, $node);
                $namespaceNode->stmts[] = $newClass;
                $statementName = implode('\\', $namespaceNode->name->parts) . '\\' . $className;
            } catch (ParentNotFoundException $e) {
                $nodeSearch->appendToRoot($newClass);
            }

            $node->class = new Node\Name\FullyQualified($statementName);
        }

        return $found;
    }
}
