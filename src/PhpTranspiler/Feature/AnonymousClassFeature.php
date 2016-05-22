<?php
declare(strict_types = 1);

namespace JanPiet\PhpTranspiler\Feature;

use JanPiet\PhpTranspiler\NodeSearch;
use JanPiet\PhpTranspiler\ParentNotFoundException;
use PhpParser\Node;

class AnonymousClassFeature implements FeatureInterface
{
    /**
     * @param NodeSearch $nodeSearch
     * @return bool
     */
    public function fix(NodeSearch $nodeSearch): bool
    {
        $found = false;
        foreach($nodeSearch->eachType(Node\Expr\New_::class) as $node) {
            if(!$node->class instanceof Node\Stmt\Class_) {
                continue;
            }
            $found = true;

            $className = uniqid('mine');
            $statementName = $className;
    
            try {
                /** @var Node\Stmt\Namespace_ $namespaceNode */
                $namespaceNode = $nodeSearch->findParent(Node\Stmt\Namespace_::class, $node);
                $statementName = implode('\\', $namespaceNode->name->parts ) . '\\' . $className;
            } catch (ParentNotFoundException $e) {

            }

            $newClass = $node->class;

            $node->class = new Node\Name\FullyQualified($statementName);

            $newClass->name = $className;

            try {
                /** @var Node\Stmt\Namespace_ $namespaceNode */
                $namespaceNode = $nodeSearch->findParent(Node\Stmt\Namespace_::class, $node);
                $namespaceNode->stmts[] = $newClass;
                
            } catch (ParentNotFoundException $e) {
                $nodeSearch->appendToRoot($newClass);
            }
        }

        return $found;
    }
}
