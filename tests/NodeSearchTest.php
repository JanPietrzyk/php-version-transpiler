<?php

namespace JanPiet\Tests\PhpTranspiler;

use JanPiet\PhpTranspiler\NodeNotFoundException;
use JanPiet\PhpTranspiler\NodeSearch;
use JanPiet\PhpTranspiler\ParentNotFoundException;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

class NodeSearchTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_finds_all_nodes()
    {
        $visitor = new class($visitorCount) extends NodeVisitorAbstract
 {
     public $nodeCount = 0;
       
     public function enterNode(\PhpParser\Node $node)
     {
         $this->nodeCount++;
     }
 };
        $search = $this->createSearch($visitor);

        $count = 0;
        foreach ($search->eachType(Node::class) as $searchResult) {
            $count++;
        }
        
        $this->assertEquals($visitor->nodeCount, $count);
    }

    public function test_it_finds_nodes_by_type()
    {
        $visitor = new class($visitorCount) extends NodeVisitorAbstract
 {
     public $nodeCount = 0;

     public function enterNode(\PhpParser\Node $node)
     {
         if ($node instanceof Node\Expr\PropertyFetch) {
             $this->nodeCount++;
         }
     }
 };
        $search = $this->createSearch($visitor);

        $count = 0;
        foreach ($search->eachType(Node\Expr\PropertyFetch::class) as $node) {
            $this->assertInstanceOf(Node\Expr\PropertyFetch::class, $node);
            $count++;
        }

        $this->assertEquals($visitor->nodeCount, $count);
    }

    public function test_it_finds_nodes_and_then_a_parent()
    {
        $visitor = new class($visitorCount) extends NodeVisitorAbstract
 {

     public $nodeCount = 0;

     public function enterNode(\PhpParser\Node $node)
     {
         if ($node instanceof Node\Expr\PropertyFetch) {
             $this->nodeCount++;
         }
     }
 };
        $search = $this->createSearch($visitor);

        $count = 0;
        foreach ($search->eachType(Node\Expr\PropertyFetch::class) as $node) {
            $parent =$search->findParent(Node\Stmt\Class_::class, $node);

            $this->assertInstanceOf(Node\Stmt\Class_::class, $parent);
            $this->assertInstanceOf(Node\Expr\PropertyFetch::class, $node);
            $count++;
        }

        $this->assertEquals($visitor->nodeCount, $count);
    }

    public function test_it_finds_no_parent_if_the_node_has_none_matching()
    {
        $visitor = new class($visitorCount) extends NodeVisitorAbstract
 {

     public $node;

     public function enterNode(\PhpParser\Node $node)
     {
         if ($node instanceof Node\Stmt\Nop) {
             $this->node = $node;
         }
     }
 };
        $search = $this->createSearch($visitor);


        $this->expectException(ParentNotFoundException::class);
        $search->findParent(Node\Stmt\Class_::class, $visitor->node);
    }

    public function test_it_replaces_sub_node_arrays()
    {
        $visitor = new class($visitorCount) extends NodeVisitorAbstract
 {

     public $node;

     public function enterNode(\PhpParser\Node $node)
     {
         if (!$this->node && $node instanceof Node\Stmt\Class_) {
             $this->node = $node;
         }
     }
 };

        $search = $this->createSearch($visitor);
        $newNode = new Node\Stmt\Property(4, [new Node\Stmt\PropertyProperty('foo')]);
        $search->replaceNode($visitor->node->stmts[2], $newNode);

        $found = false;
        foreach ($search->eachType(Node\Stmt\Property::class) as $node) {
            if ($node === $newNode) {
                $found = true;
            }
        }

        $this->assertTrue($found);
    }

    public function test_it_throws_when_sub_node_can_not_be_found()
    {
        $search = $this->createSearch();
        $newNode = new Node\Stmt\Property(4, [new Node\Stmt\PropertyProperty('foo')]);

        $this->expectException(NodeNotFoundException::class);
        $search->replaceNode($newNode, $newNode);
    }

    private function createSearch(NodeVisitor $visitor = null)
    {
        $parser = (new ParserFactory())->create(ParserFactory::ONLY_PHP7);
        $nodes = $parser->parse(file_get_contents(__DIR__ . '/_fixtures/search.php'));

        if ($visitor) {
            $traverser = new NodeTraverser();
            $traverser->addVisitor($visitor);
            $traverser->traverse($nodes);
        }

        return new NodeSearch($nodes);
    }
}
