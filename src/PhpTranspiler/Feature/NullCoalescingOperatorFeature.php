<?php


namespace JanPiet\PhpTranspiler\Feature;

use JanPiet\PhpTranspiler\NodeSearch;
use PhpParser\Node;

class NullCoalescingOperatorFeature implements FeatureInterface
{
    /**
     * @param NodeSearch $nodeSearch
     * @return bool
     */
    public function fix(NodeSearch $nodeSearch): bool
    {
        $found = false;
        /** @var Node\Expr\BinaryOp\Coalesce $node */
        foreach ($nodeSearch->eachType(Node\Expr\BinaryOp\Coalesce::class) as $node) {
            $found = true;
            $newNode = new Node\Expr\Ternary(
                new Node\Expr\Isset_([$node->left]),
                $node->left,
                $node->right
            );

            $nodeSearch->replaceNode($node, $newNode);
        }
        
        return $found;
    }
}
