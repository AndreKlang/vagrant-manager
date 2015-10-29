<?php

namespace App\Command;

use App\Command;
use App\Service\Vagrant;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class UpCommand extends Command
{
    protected function configure(){
        $this
            ->setName('up')
            ->setDescription('Bring up box')
            ->addArgument(
                'identifyer',
                InputArgument::OPTIONAL,
                'Select which box to start (hash or number)'
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
        $this->browseInteraction($input, $output, "Select box to start");
    }

    protected function execute(InputInterface $input, OutputInterface $output){

        $vagrant = new Vagrant();

        $host = $vagrant->lookupBox($input->getArgument("identifyer"));
        if($host !== null){
            $id = $host->getData("id");
            $output->writeln(sprintf("<fg=yellow>Bringing up:</> %s <fg=blue>%s</>",$host->getData("name"),$host->getData("dir")));
        } else {
            $id = null;
        }

        $result = $vagrant->commandUp($id);
    }
}
