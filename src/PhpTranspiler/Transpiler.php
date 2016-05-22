<?php


namespace JanPiet\PhpTranspiler;

use JanPiet\PhpTranspiler\Feature\FeatureInterface;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

class Transpiler
{
    public function transpileFeature(string $sourceFileContents, FeatureInterface $feature): string
    {
        $parser = (new ParserFactory())->create(ParserFactory::ONLY_PHP7);
        $nodes = $parser->parse($sourceFileContents);

        $search = new NodeSearch($nodes);
        $feature->fix($search);

        $prettyPrinter = new Standard();

        return $prettyPrinter->prettyPrintFile($search->getTree());
    }
}
