<?php


namespace JanPiet\PhpTranspiler\Feature;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;

class NullCoalescingOperatorVisitor extends NodeVisitorAbstract
{
    /**
     * @param Node $node
     * @return null|Node|void
     */
    public function enterNode(\PhpParser\Node $node)
    {

        if(!$node instanceof Node\Expr\BinaryOp\Coalesce) {
            return;
        }

        throw new \Exception('Not implementable through visitors');

    }
}