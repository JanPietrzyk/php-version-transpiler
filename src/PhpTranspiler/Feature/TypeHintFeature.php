<?php
declare(strict_types = 1);

namespace JanPiet\PhpTranspiler\Feature;

use JanPiet\PhpTranspiler\NodeSearch;
use PhpParser\Node;

class TypeHintFeature implements FeatureInterface
{
    private $php7OnlyTypeHints = ['int', 'string', 'float', 'bool'];

    /**
     * @param NodeSearch $nodeSearch
     * @return bool
     */
    public function fix(NodeSearch $nodeSearch): bool
    {
        $found = false;
        foreach ($nodeSearch->eachType(Node\Param::class) as $node) {
            if (!in_array($node->type, $this->php7OnlyTypeHints)) {
                continue;
            }

            $found = true;
            $node->type = null;
        }

        return $found;
    }
}
