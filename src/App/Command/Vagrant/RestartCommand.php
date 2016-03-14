<?php

namespace App\Command\Vagrant;

use App\Command;
use App\Service\Vagrant;
use App\Command\VagrantCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RestartCommand extends VagrantCommand
{
    public $type = "restart";
    public $action = "restart";

    protected function execute(InputInterface $input, OutputInterface $output){
        $vagrant = new Vagrant();

        $hostList = $this->getHostList($input, $output);

        if($hostList === null) {
            $vagrant->commandHalt();
            $vagrant->commandUp();
        } else {
            $count = count($hostList);

            # handle all boxes that match the inputStr
            foreach($hostList as $host) {
                /** @var \App\Service\Vagrant\Host $host */;

                $output->writeln(sprintf(
                    "<fg=yellow>Restaring:</> %s <fg=blue>%s</>",
                    $host->getData("name"),
                    $host->getData("dir")
                ));

                $vagrant->commandHalt($host->getData("id"));
                $vagrant->commandUp($host->getData("id"));
            }

            # print success-message
            if($count) {
                $output->writeln(sprintf(
                    "<fg=green>Done:</> Restarted <fg=blue>%s</> %s",
                    $count,
                    ($count > 1 ? 'boxes' : "box")
                ));
            }
        }
    }
}
