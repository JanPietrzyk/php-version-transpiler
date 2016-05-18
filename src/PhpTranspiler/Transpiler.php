<?php


namespace JanPiet\PhpTranspiler;

use PhpParser\NodeVisitor;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use PhpParser\NodeTraverser;

class Transpiler
{
    public function transpile(string $sourceFile, string $targetFile, NodeVisitor $visitor)
    {
        $parser = (new ParserFactory())->create(ParserFactory::ONLY_PHP7);
        $nodes = $parser->parse(file_get_contents($sourceFile));
        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor);

        $nodes = $traverser->traverse($nodes);

        $prettyPrinter = new Standard();
        $result = $prettyPrinter->prettyPrintFile($nodes);
        
        @mkdir(dirname($targetFile), 0755, true);
        file_put_contents($targetFile, $result);
    }
}