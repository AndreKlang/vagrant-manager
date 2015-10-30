<?php

namespace App\Command;

use App\Command;
use App\Service\Vagrant;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SuspendCommand extends Command
{
    private $type = "suspend";
    private $action = "suspend";

    protected function configure(){
        $this
            ->setName($this->type)
            ->setDescription(ucfirst($this->action).' box')
            ->addArgument(
                'identifyer',
                InputArgument::OPTIONAL,
                'Select which box to '.$this->action.' (number, hash or "matching rules")'
            )
            ->addOption(
                'browse',
                'b',
                InputOption::VALUE_NONE,
                'Allows you to select one from a list'
            )
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output){
        $this->browseInteraction($input, $output, "Select box to ".$this->action);
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $this->vagrantProcessCommand($input, $output, $this->type);
    }
}
