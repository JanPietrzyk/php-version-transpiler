<?php

namespace JanPiet\Tests\PhpTranspiler\Feature;

use JanPiet\PhpTranspiler\Feature\FeatureInterface;
use JanPiet\PhpTranspiler\Transpiler;
use PhpParser\NodeVisitor;

abstract class FeatureTestcase extends \PHPUnit_Framework_TestCase
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
        $destinationFileName = __DIR__ . '/../_out/' . $this->getFixturePath() . '/' . $file;

        $transpiler = new Transpiler();
        $transpiler->transpileFeature(
            __DIR__ . '/../_fixtures/' . $this->getFixturePath() . '/' . $file,
            $destinationFileName,
            $this->createFeature()
        );

        $this->assertFileExists($destinationFileName);

        return $destinationFileName;
    }

    abstract protected function createFeature(): FeatureInterface;

    abstract protected function getFixturePath(): string;
}
