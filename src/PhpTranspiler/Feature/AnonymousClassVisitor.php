<?php
declare(strict_types = 1);

namespace JanPiet\PhpTranspiler\Feature;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;

class AnonymousClassVisitor extends NodeVisitorAbstract
{
    public $appendNodes;

    private $currentNamespace = [];

    /**
     * @param Node $node
     * @return null|Node|void
     */
    public function enterNode(\PhpParser\Node $node)
    {
        if($node instanceof Node\Stmt\Namespace_) {
            $this->currentNamespace = $node->name->parts;
            return;
        }

        if(!$node instanceof Node\Expr\New_) {
            return;
        }

        if(!$node->class instanceof Node\Stmt\Class_) {
            return;
        }


        $className = uniqid('mine');
        $statementName = $className;

        if($this->currentNamespace) {
            $statementName = implode('\\', $this->currentNamespace ) . '\\' . $className;
        }

        $node->class->name = $className;
        $this->appendNodes = $node->class;
        $node->class = new Node\Name\FullyQualified($statementName);
    }
}
