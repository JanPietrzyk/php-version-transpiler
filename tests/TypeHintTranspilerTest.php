<?php

namespace JanPiet\Tests\PhpTranspiler;

use JanPiet\PhpTranspiler\TypeHintVisitor;
use PhpParser\NodeVisitor;

class TypeHintTranspilerTest extends TranspileTestcase
{
    public function test_function_it_transpiles_simple_function_with_type_hints()
    {
        $createdFile = $this->transpile('function.php');

        $this->assertFileContains('return \'foo\';', $createdFile);
        $this->assertFileNotContains('int', $createdFile);
    }

    public function test_function_it_does_not_remove_class_type_hints()
    {
        $createdFile = $this->transpile('function-with-class-type-hint.php');
        $this->assertFileContains('\\stdClass', $createdFile);
    }

    public function test_function_it_does_only_remove_scalar_type_hints()
    {
        $createdFile = $this->transpile('function-with-mixed-type-hints.php');
        $this->assertFileContains('\\stdClass', $createdFile);
        $this->assertFileNotContains('float', $createdFile);
    }

    public function test_class_it_transpiles_simple_function_with_type_hints()
    {
        $createdFile = $this->transpile('class.php');

        $this->assertFileContains('return \'foo\';', $createdFile);
        $this->assertFileNotContains('int', $createdFile);
    }

    public function test_class_it_does_only_remove_scalar_type_hints()
    {
        $createdFile = $this->transpile('class-with-mixed-type-hints.php');

        $this->assertFileContains('return \'foo\';', $createdFile);
        $this->assertFileNotContains('int', $createdFile);
    }


    /**
     * @return NodeVisitor
     */
    protected function createNodeVisitor(): NodeVisitor
    {
        return new TypeHintVisitor();
    }

    protected function getFixturePath(): string
    {
        return 'type-hint';
    }
}