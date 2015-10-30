<?php

namespace App\Command\Vagrant\Restart;

use App\Command;
use App\Service\Vagrant;
use App\Command\Vagrant\RestartCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class AllCommand extends RestartCommand
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
            "identifyer" => "*"
        ));
        $command->run($argInput, $output);

    }
}
