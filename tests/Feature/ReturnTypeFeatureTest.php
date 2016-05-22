<?php

namespace JanPiet\Tests\PhpTranspiler\Feature;

use JanPiet\PhpTranspiler\Feature\FeatureInterface;
use JanPiet\PhpTranspiler\Feature\ReturnTypeFeature;
use PhpParser\NodeVisitor;

class ReturnTypeFeatureTest extends FeatureTestcase
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
     * @return FeatureInterface
     */
    protected function createFeature(): FeatureInterface
    {
        return new ReturnTypeFeature();
    }

    protected function getFixturePath(): string
    {
        return 'return-type';
    }
}
