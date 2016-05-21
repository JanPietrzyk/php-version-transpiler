<?php

namespace JanPiet\Tests\PhpTranspiler\Feature;

use JanPiet\PhpTranspiler\Feature\AnonymousClassVisitor;
use PhpParser\NodeVisitor;

class AnonymousClassTranspilerTest extends TranspileTestcase
{
    public function test_it_adds_new_classes_to_a_file_that_contained_anonymous_classes()
    {
        $createdFile = $this->transpile('function-creates-class.php');

        $this->assertFileNotContains('new class', $createdFile);
        $this->assertFileContains('new \mine', $createdFile);
        $this->assertFileContains('class mine', $createdFile);
    }

    public function test_it_adds_new_classes_to_a_file_that_contained_anonymous_classes_with_namespaces()
    {
        $createdFile = $this->transpile('namespaced-function-creates-class.php');

        $this->assertFileNotContains('new class', $createdFile);
        $this->assertFileContains('new \Foo\Bar\mine', $createdFile);
        $this->assertFileContains('class mine', $createdFile);
    }

    /**
     * @return NodeVisitor
     */
    protected function createNodeVisitor(): NodeVisitor
    {
        return new AnonymousClassVisitor();
    }

    protected function getFixturePath(): string
    {
        return 'anonymous-class';
    }
}