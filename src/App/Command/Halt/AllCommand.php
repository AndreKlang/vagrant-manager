<?php

namespace App\Command\Halt;

use App\Command;
use App\Service\Vagrant;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AllCommand extends Command
{
    protected function configure(){
        $this
            ->setName('halt:all')
            ->setDescription('Halt All boxes')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $vagrant = new Vagrant();
        $count = 0;
        foreach($vagrant->getAllHosts() as $box){
            /** @var \App\Service\Vagrant\Host $box */
            if($box->getData("state") == "poweroff") continue;

            $output->writeln("<fg=yellow>Halting: ".$box->getData("dir")."</>");
            $vagrant->commandHalt($box->getData("id"));
            $count++;
        }

        if(!$count) $output->writeln("<fg=yellow>No running hosts</>");
        else $output->writeln("<fg=green>Halted all hosts (".$count.")</>");
    }
}
