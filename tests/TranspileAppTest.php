<?php

namespace JanPiet\Tests\PhpTranspiler;

use JanPiet\PhpTranspilerApp\TranspilerApplication;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Process\Process;

class TranspileAppTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $process = new Process('rm -R ' .  __DIR__ . '/_appout/*');
        $process->run();
    }


    public function test_binary_execution_against_fixtures()
    {
        $this->assertFileNotExists(__DIR__ . '/_appout/return-type');

        $process = new Process(__DIR__ . '/../php-version-transpiler tests/_fixtures tests/_appout/');
        $process->run();

        $this->assertTrue($process->isSuccessful(), $process->getErrorOutput());
        $this->assertSynchronicity();
    }

    public function test_class_execution_against_fixtures()
    {
        $this->assertFileNotExists(__DIR__ . '/_appout/return-type');

        $application = new TranspilerApplication();
        $application->setAutoExit(false);

        $appTester = new ApplicationTester($application);
        $appTester->run([
           'source' => __DIR__ . '/_fixtures',
            'destination' =>__DIR__ . '/_appout/'
        ]);

        $this->assertSynchronicity();
    }

    private function assertSynchronicity()
    {
        unlink(__DIR__ . '/_appout/search.php');
        $finder = new Finder();
        $finder->files()->in(__DIR__ . '/_appout');

        $this->assertGreaterThan(5, $finder->count());
        /** @var  SplFileInfo $file */
        foreach ($finder as $file) {
            $this->assertEquals(
                $file->getContents(),
                file_get_contents(__DIR__ . '/_out/' . $file->getRelativePath() . '/' . $file->getFilename())
            );
        }
    }
}
