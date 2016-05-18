<?php

namespace JanPiet\Tests\PhpTranspiler;


use JanPiet\PhpTranspiler\Transpiler;
use PhpParser\NodeVisitor;

abstract class TranspileTestcase extends \PHPUnit_Framework_TestCase
{
    protected function assertFileContains(string $string, string $file)
    {
        $this->assertTrue(false !== strpos(file_get_contents($file), $string), "Could not find $string in $file");
    }

    protected function assertFileNotContains(string $string, string $file)
    {
        $this->assertTrue(false === strpos(file_get_contents($file), $string), "Could find $string in $file");
    }

    protected function transpile(string $file):string
    {
        $transpiler = new Transpiler();
        $transpiler->transpile(
            __DIR__ . '/_fixtures/' . $this->getFixturePath() . '/' . $file,
            __DIR__ . '/_out/' . $this->getFixturePath() . '/' . $file,
            $this->createNodeVisitor()
        );

        $this->assertFileExists(__DIR__ . '/_out/' . $this->getFixturePath() . '/' . $file);

        return __DIR__ . '/_out/' . $this->getFixturePath() . '/' . $file;
    }

    abstract protected function createNodeVisitor(): NodeVisitor;

    abstract protected function getFixturePath(): string;
}