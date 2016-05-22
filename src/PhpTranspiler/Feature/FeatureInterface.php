<?php


namespace JanPiet\PhpTranspiler\Feature;


use JanPiet\PhpTranspiler\NodeSearch;

interface FeatureInterface
{
    /**
     * @param NodeSearch $nodeSearch
     * @return bool
     */
    public function fix(NodeSearch $nodeSearch): bool;

}