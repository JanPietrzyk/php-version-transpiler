<?php


namespace JanPiet\PhpTranspiler\Feature;

use JanPiet\PhpTranspiler\NodeSearch;
use PhpParser\Node;

class SpaceshipOperatorFeature implements FeatureInterface
{

    /**
     * @param NodeSearch $nodeSearch
     * @return bool
     */
    public function fix(NodeSearch $nodeSearch): bool
    {
        $found = false;

        /** @var Node\Expr\BinaryOp\Spaceship $node */
        foreach ($nodeSearch->eachType(Node\Expr\BinaryOp\Spaceship::class) as $node) {
            $found = true;

            $newNode = new Node\Expr\Ternary(
                new Node\Expr\BinaryOp\Smaller($node->left, $node->right),
                new Node\Scalar\LNumber(-1),
                new Node\Expr\Ternary(
                    new Node\Expr\BinaryOp\Equal($node->left, $node->right),
                new Node\Scalar\LNumber(0),
                new Node\Scalar\LNumber(1)
                )
            );

            $nodeSearch->replaceNode($node, $newNode);
        }

        return $found;
    }
}
