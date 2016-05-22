<?php

namespace JanPiet\Tests\PhpTranspiler;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Process\Process;

class TranspileAppTest extends \PHPUnit_Framework_TestCase
{
    public function test_execution_against_fixtures()
    {
        $process = new Process(__DIR__ . '/../php-version-transpiler tests/_fixtures tests/_appout/');

        $process->run();
        $this->assertTrue($process->isSuccessful(), $process->getErrorOutput());

        unlink(__DIR__ . '/_appout/search.php');
        $finder = new Finder();
        $finder->files()->in(__DIR__ . '/_appout');

        /** @var  SplFileInfo $file */
        foreach ($finder as $file) {
            $this->assertEquals(
                $file->getContents(),
                file_get_contents(__DIR__ . '/_out/' . $file->getRelativePath() . '/' . $file->getFilename())
            );
        }
    }
}
