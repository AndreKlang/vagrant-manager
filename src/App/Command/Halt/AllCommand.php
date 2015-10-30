<?php

namespace App\Command\Halt;

use App\Command;
use App\Service\Vagrant;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class AllCommand extends Command
{
    private $type = "halt";
    private $action = "stop";

    protected function configure(){
        $this
            ->setName($this->type.':all')
            ->setDescription(ucfirst($this->action).' All boxes')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){

        $command = $this->getApplication()->find($this->type);
        $greetInput = new ArrayInput(array(
            "identifyer" => "*"
        ));
        $command->run($greetInput, $output);

    }
}
