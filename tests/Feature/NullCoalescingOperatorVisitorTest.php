<?php


namespace JanPiet\Tests\PhpTranspiler\Feature;

use JanPiet\PhpTranspiler\Feature\NullCoalescingOperatorVisitor;
use PhpParser\NodeVisitor;

class NullCoalescingOperatorVisitorTest extends TranspileTestcase
{
    protected function createNodeVisitor(): NodeVisitor
    {
        return new NullCoalescingOperatorVisitor();
    }

    protected function getFixturePath(): string
    {
        return 'null-coalescing-operator';
    }
}