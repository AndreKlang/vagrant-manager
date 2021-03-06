<?php

namespace Klang\App\Command\Vagrant;

use Klang\App\Command;
use Klang\App\Service\Vagrant;
use Klang\App\Command\VagrantCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class SshCommand extends VagrantCommand
{
    public $type = "ssh";
    public $action = "ssh";

    protected function configure(){
        parent::configure();

        $this->setDescription("Start an ssh-session into a box");
        $this->addOption(
            'start',
            's',
            InputOption::VALUE_NONE,
            'Start the box if necessary'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $vagrant = new Vagrant();

        $hostList = $this->getHostList($input, $output);

        if($hostList === null) {

            if($input->getOption("start")) {
                $vagrant->commandUp();
            }

            $vagrant->commandSsh();
        } else {
            $count = count($hostList);

            # handle all boxes that match the inputStr
            foreach($hostList as $host) {
                /** @var \Klang\App\Service\Vagrant\Host $host */;

                if($host->getData("state") != 'running') {

                    if($input->getOption("start")) {
                        $output->writeln(sprintf(
                            "<fg=yellow>Starting:</> %s <fg=blue>%s</>",
                            $host->getData("name"),
                            $host->getData("dir")
                        ));
                        $vagrant->commandUp($host->getData("id"));
                    } else {
                        $count--;
                        $output->writeln(sprintf(
                            "<bg=red>Box %s (%s) is not running!</>",
                            $host->getData("name"),
                            $host->getData("dir")
                        ));
                        $output->writeln(sprintf("<fg=yellow>Use option --start to start it for you</>"));
                        continue;
                    }
                }

                $output->writeln(sprintf(
                    "<fg=yellow>Starting ssh-session to:</> %s <fg=blue>%s</>",
                    $host->getData("name"),
                    $host->getData("dir")
                ));

                $vagrant->commandSsh($host->getData("id"));
            }

            # print success-message
            if($count) {
                $output->writeln(sprintf(
                    "<fg=green>Done:</> ssh-session ended for <fg=blue>%s</> %s",
                    $count,
                    ($count > 1 ? 'boxes' : "box")
                ));
            }
        }
    }
}
