<?php

namespace JanPiet\PhpTranspilerApp;

use JanPiet\PhpTranspiler\Feature\AnonymousClassFeature;
use JanPiet\PhpTranspiler\Feature\NullCoalescingOperatorFeature;
use JanPiet\PhpTranspiler\Feature\ReturnTypeFeature;
use JanPiet\PhpTranspiler\Feature\SpaceshipOperatorFeature;
use JanPiet\PhpTranspiler\Feature\StrictTypesFeature;
use JanPiet\PhpTranspiler\Feature\TypeHintFeature;
use JanPiet\PhpTranspiler\Transpiler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Example command for testing purposes.
 */
class TranspileCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('transpile')
            ->setDescription('Transpile a source file to a destination')
            ->addArgument('source', InputArgument::REQUIRED, 'Where is your PHP7 source?')
            ->addArgument('destination', InputArgument::REQUIRED, 'Where should the PHP5.6 compatible files be put?')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $transpiler = new Transpiler();

        $filesystem = new Filesystem();
        $finder = new Finder();
        $finder->files()->in($input->getArgument('source'));

        /** @var  SplFileInfo $file */
        foreach ($finder as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $transpiled = $transpiler->transpileFeature(
                $file->getContents(),
                new AnonymousClassFeature(),
                new NullCoalescingOperatorFeature(),
                new ReturnTypeFeature(),
                new SpaceshipOperatorFeature(),
                new TypeHintFeature(),
                new StrictTypesFeature()
            );

            $filesystem->dumpFile($input->getArgument('destination') . '/' . $file->getRelativePath() . '/' . $file->getFilename(), $transpiled);
        }
    }
}
