<?php


namespace JanPiet\PhpTranspiler\Feature;

use JanPiet\PhpTranspiler\NodeSearch;
use PhpParser\Node;

class NullCoalescingOperatorVisitor implements FeatureInterface
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

/*
[expr] => PhpParser\Node\Expr\Ternary Object
                (
                    [cond] => PhpParser\Node\Expr\Isset_ Object
                        (
                            [vars] => Array
                                (
                                    [0] => PhpParser\Node\Expr\ArrayDimFetch Object
                                        (
                                            [var] => PhpParser\Node\Expr\Variable Object
                                                (
                                                    [name] => foo
                                                    [attributes:protected] => Array
                                                        (
                                                            [startLine] => 5
                                                            [endLine] => 5
                                                        )

                                                )

                                            [dim] => PhpParser\Node\Scalar\String_ Object
                                                (
                                                    [value] => user
                                                    [attributes:protected] => Array
                                                        (
                                                            [startLine] => 5
                                                            [endLine] => 5
                                                            [kind] => 1
                                                        )

                                                )

                                            [attributes:protected] => Array
                                                (
                                                    [startLine] => 5
                                                    [endLine] => 5
                                                )

                                        )

                                )

                            [attributes:protected] => Array
                                (
                                    [startLine] => 5
                                    [endLine] => 5
                                )

                        )

                    [if] => PhpParser\Node\Expr\ArrayDimFetch Object
                        (
                            [var] => PhpParser\Node\Expr\Variable Object
                                (
                                    [name] => foo
                                    [attributes:protected] => Array
                                        (
                                            [startLine] => 5
                                            [endLine] => 5
                                        )

                                )

                            [dim] => PhpParser\Node\Scalar\String_ Object
                                (
                                    [value] => user
                                    [attributes:protected] => Array
                                        (
                                            [startLine] => 5
                                            [endLine] => 5
                                            [kind] => 1
                                        )

                                )

                            [attributes:protected] => Array
                                (
                                    [startLine] => 5
                                    [endLine] => 5
                                )

                        )

                    [else] => PhpParser\Node\Scalar\String_ Object
                        (
                            [value] => nobody
                            [attributes:protected] => Array
                                (
                                    [startLine] => 5
                                    [endLine] => 5
                                    [kind] => 1
                                )

                        )

                    [attributes:protected] => Array
                        (
                            [startLine] => 5
                            [endLine] => 5
                        )

                )

            [attributes:protected] => Array
                (
                    [startLine] => 5
                    [endLine] => 5
                )

        )

*/