<?php
declare(strict_types = 1);

namespace JanPiet\PhpTranspiler;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;

class ReturnTypeVisitor extends NodeVisitorAbstract
{
    /**
     * @param Node $node
     * @return null|Node|void
     */
    public function enterNode(\PhpParser\Node $node)
    {
        if($node instanceof Node\Stmt\Function_) {
            $node->returnType = null;
            return;
        }

        if($node instanceof Node\Stmt\ClassMethod) {
            $node->returnType = null;
            return;
        }
    }
}
