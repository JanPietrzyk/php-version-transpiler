<?php


namespace JanPiet\PhpTranspiler;

use JanPiet\PhpTranspiler\Feature\FeatureInterface;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

class Transpiler
{
    /**
     * @param string $sourceFileContents
     * @param FeatureInterface[] ...$feature
     * @return string
     */
    public function transpileFeature(string $sourceFileContents, FeatureInterface ...$features): string
    {
        $parser = (new ParserFactory())->create(ParserFactory::ONLY_PHP7);
        $nodes = $parser->parse($sourceFileContents);

        $search = new NodeSearch($nodes);

        foreach ($features as $feature) {
            $feature->fix($search);
        }

        $prettyPrinter = new Standard();

        return $prettyPrinter->prettyPrintFile($search->getTree());
    }
}
