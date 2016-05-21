<?php
declare(strict_types = 1);

namespace JanPiet\PhpTranspiler\Feature;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;

class TypeHintVisitor extends NodeVisitorAbstract
{
    private $php7OnlyTypeHints = ['int', 'string', 'float', 'bool'];

    /**
     * @param Node $node
     * @return null|Node|void
     */
    public function enterNode(\PhpParser\Node $node)
    {

        if (!$node instanceof Node\Param) {
            return;
        }

        if (!in_array($node->type, $this->php7OnlyTypeHints)) {
            return;
        }

        $node->type = null;
    }
}
