<?php


namespace JanPiet\Tests\PhpTranspiler\Feature;

use JanPiet\PhpTranspiler\Feature\FeatureInterface;
use JanPiet\PhpTranspiler\Feature\NullCoalescingOperatorVisitor;
use PhpParser\NodeVisitor;

class NullCoalescingOperatorFeatureTest extends FeatureTestcase
{
    public function test_it_replaces_the_operator() {
        $createdFile = $this->transpile('default.php');

        $this->assertFileNotContains('??', $createdFile);
        $this->assertFileContains('?', $createdFile);
        $this->assertFileContains('= isset(', $createdFile);
        $this->assertFileContains(':', $createdFile);
    }

    protected function createFeature(): FeatureInterface
    {
        return new NullCoalescingOperatorVisitor();
    }

    protected function getFixturePath(): string
    {
        return 'null-coalescing-operator';
    }
}