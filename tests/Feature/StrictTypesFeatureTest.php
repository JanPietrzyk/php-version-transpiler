<?php declare (strict_types = 1);


namespace JanPiet\Tests\PhpTranspiler\Feature;

use JanPiet\PhpTranspiler\Feature\FeatureInterface;
use JanPiet\PhpTranspiler\Feature\StrictTypesFeature;

class StrictTypesFeatureTest extends FeatureTestcase
{
    public function test_it_removes_the_declaration_only()
    {
        $createdFile = $this->transpile('default.php');

        $this->assertFileNotContains('strict_types', $createdFile);
        $this->assertFileNotContains('declare(', $createdFile);
    }

    protected function createFeature(): FeatureInterface
    {
        return new StrictTypesFeature();
    }

    protected function getFixturePath(): string
    {
        return 'strict-types';
    }
}
