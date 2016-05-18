<?php

namespace JanPiet\Tests\PhpTranspiler;

use JanPiet\PhpTranspiler\ReturnTypeVisitor;
use PhpParser\NodeVisitor;

class ReturnTypeTranspilerTest extends TranspileTestcase
{
    public function test_it_transpiles_return_types()
    {
        $createdFile = $this->transpile('function-with-return-type.php');

        $this->assertFileContains('return \'foo\';', $createdFile);
        $this->assertFileNotContains(':int', $createdFile);
        $this->assertFileNotContains(': int', $createdFile);
    }

    public function test_it_transpiles_return_types_on_methods()
    {
        $createdFile = $this->transpile('method-with-return-type.php');

        $this->assertFileContains('return \'foo\';', $createdFile);
        $this->assertFileNotContains(':int', $createdFile);
        $this->assertFileNotContains(': int', $createdFile);
    }

    public function test_it_transpiles_return_types_on_abstract_methods()
    {
        $createdFile = $this->transpile('abstract-method-with-return-type.php');

        $this->assertFileNotContains(':int', $createdFile);
        $this->assertFileNotContains(': int', $createdFile);
    }

    public function test_it_does_not_affect_missing_return_types()
    {
        $createdFile = $this->transpile('function-without-return-type.php');

        $this->assertFileContains('return \'foo\';', $createdFile);
        $this->assertFileNotContains(':int', $createdFile);
        $this->assertFileNotContains(': int', $createdFile);
    }

    /**
     * @return NodeVisitor
     */
    protected function createNodeVisitor(): NodeVisitor
    {
        return new ReturnTypeVisitor();
    }

    protected function getFixturePath(): string
    {
        return 'return-type';
    }
}