<?php declare (strict_types = 1);


namespace JanPiet\PhpTranspiler\Feature;

use JanPiet\PhpTranspiler\NodeSearch;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\DeclareDeclare;

class StrictTypesFeature implements FeatureInterface
{

    /**
     * @param NodeSearch $nodeSearch
     * @return bool
     */
    public function fix(NodeSearch $nodeSearch): bool
    {
        $found = false;

        foreach ($nodeSearch->eachType(Declare_::class) as $declareNode) {
            $newDeclares = [];

            /** @var DeclareDeclare $declareStatement */
            foreach ($declareNode->declares as $declareStatement) {
                if ($declareStatement->key === 'strict_types') {
                    $found = true;
                    continue;
                }

                $newDeclares[] = $declareNode;
            }

            if ($newDeclares) {
                $declareNode->declares = $newDeclares;
                continue;
            }

            $nodeSearch->removeNode($declareNode);
        }

        return $found;
    }
}
