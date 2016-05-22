<?php


namespace JanPiet\Tests\PhpTranspiler\Feature;

use JanPiet\PhpTranspiler\Feature\FeatureInterface;
use JanPiet\PhpTranspiler\Feature\NullCoalescingOperatorVisitor;
use PhpParser\NodeVisitor;

class NullCoalescingOperatorFeatureTest extends FeatureTestcase
{
    protected function createFeature(): FeatureInterface
    {
        return new NullCoalescingOperatorVisitor();
    }

    protected function getFixturePath(): string
    {
        return 'null-coalescing-operator';
    }
}