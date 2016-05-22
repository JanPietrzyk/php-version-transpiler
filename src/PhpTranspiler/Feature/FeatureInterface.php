<?php


namespace JanPiet\PhpTranspiler\Feature;


use JanPiet\PhpTranspiler\NodeSearch;

interface FeatureInterface
{
//    /**
//     * @param NodeSearch $nodeSearch
//     * @return bool
//     */
//    public function applies(NodeSearch $nodeSearch): bool;

    /**
     * @param NodeSearch $nodeSearch
     */
    public function fix(NodeSearch $nodeSearch): bool;

}