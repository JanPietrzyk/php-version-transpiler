<?php
declare(strict_types = 1);

namespace JanPiet\PhpTranspiler\Feature;

use JanPiet\PhpTranspiler\NodeSearch;
use PhpParser\Node;

class ReturnTypeFeature implements FeatureInterface
{
    /**
     * @param NodeSearch $nodeSearch
     * @return bool
     */
    public function fix(NodeSearch $nodeSearch): bool
    {
        $found = false;
        foreach($nodeSearch->eachType(Node\Stmt\Function_::class, Node\Stmt\ClassMethod::class) as $node) {
            if($node->returnType) {
                $found = true;
            }

            $node->returnType = null;
        }

        return $found;
    }
}
