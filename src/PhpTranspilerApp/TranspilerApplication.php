<?php


namespace JanPiet\PhpTranspilerApp;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;

class TranspilerApplication extends Application
{
    protected function getCommandName(InputInterface $input)
    {
        return 'transpile';
    }

    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();

        $defaultCommands[] = new TranspileCommand();

        return $defaultCommands;
    }

    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        $inputDefinition->setArguments();

        return $inputDefinition;
    }
}
