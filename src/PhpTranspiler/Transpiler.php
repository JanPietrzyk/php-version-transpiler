<?php


namespace JanPiet\PhpTranspiler;

use JanPiet\PhpTranspiler\Feature\FeatureInterface;
use PhpParser\NodeVisitor;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

class Transpiler
{
    public function transpileFeature(string $sourceFile, string $targetFile, FeatureInterface $feature)
    {
        $parser = (new ParserFactory())->create(ParserFactory::ONLY_PHP7);
        $nodes = $parser->parse(file_get_contents($sourceFile));

        $search = new NodeSearch($nodes);
        $feature->fix($search);

        $prettyPrinter = new Standard();
        $result = $prettyPrinter->prettyPrintFile($search->getTree());
        
        @mkdir(dirname($targetFile), 0755, true);
        file_put_contents($targetFile, $result);
    }
}
