<?php

namespace Klang\App\Command\Vagrant\Suspend;

use Klang\App\Command;
use Klang\App\Service\Vagrant;
use Klang\App\Command\Vagrant\SuspendCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class AllCommand extends SuspendCommand
{
    protected function configure(){
        $this
            ->setName($this->type.':all')
            ->setDescription(ucfirst($this->action).' All boxes')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){

        $command = $this->getApplication()->find($this->type);
        $argInput = new ArrayInput(array(
            "identifier" => "*"
        ));
        $command->run($argInput, $output);

    }
}
