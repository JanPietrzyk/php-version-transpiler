<?php


namespace JanPiet\Tests\PhpTranspiler\Feature;

use JanPiet\PhpTranspiler\Feature\FeatureInterface;
use JanPiet\PhpTranspiler\Feature\SpaceshipOperatorFeature;
use PhpParser\NodeVisitor;

class SpaceshipOperatorFeatureTest extends FeatureTestcase
{
    public function test_if_the_replacemenet_works()
    {
        $fixtures = [
            [0, 0, 0],
            [-10, 10, -1],
            [10, -10, 1],
        ];

        foreach ($fixtures as $fixture) {
            $result = ($fixture[0] < $fixture[1] ? -1 : ($fixture[0] == $fixture[1] ? 0 : 1));
            $this->assertEquals($fixture[2], $result);
        }
    }

    public function test_it_replaces_the_operator()
    {
        $createdFile = $this->transpile('default.php');

        $this->assertFileNotContains('<=>', $createdFile);
        $this->assertFileContains('?', $createdFile);
        $this->assertFileContains(':', $createdFile);
    }

    protected function createFeature(): FeatureInterface
    {
        return new SpaceshipOperatorFeature();
    }

    protected function getFixturePath(): string
    {
        return 'spaceship-operator';
    }
}
