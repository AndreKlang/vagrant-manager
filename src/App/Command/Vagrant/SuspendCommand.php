<?php

namespace App\Command\Vagrant;

use App\Command;
use App\Service\Vagrant;
use App\Command\VagrantCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SuspendCommand extends VagrantCommand
{
    var $type = "suspend";
    var $action = "suspend";

    /**
     * {@inheritdoc}
     */
    public function getHostList(InputInterface $input, OutputInterface $output){

        $ignoreStatuses = array(
            "saved",
            "poweroff",
            "aborted"
        );

        $list = parent::getHostList($input, $output);
        if(is_array($list)) {

            foreach($list as $key => $host){
                /** @var \App\Service\Vagrant\Host $host */

                # remove ignored from the list
                if(in_array($host->getData("state"),$ignoreStatuses)) unset($list[$key]);
            }
        }

        return $list;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $vagrant = new Vagrant();

        $hostList = $this->getHostList($input, $output);

        if($hostList === null){
            $vagrant->commandSuspend();
        } else {
            $count = count($hostList);

            # handle all boxes that match the inputStr
            foreach($hostList as $host){
                /** @var \App\Service\Vagrant\Host $host */;

                $output->writeln(sprintf("<fg=yellow>Suspending:</> %s <fg=blue>%s</>",
                    $host->getData("name"),$host->getData("dir")
                ));

                $vagrant->commandSuspend($host->getData("id"));
            }

            # print success-message
            if($count){
                $output->writeln(sprintf("<fg=green>Done:</> Suspended <fg=blue>%s</> %s",
                    $count,($count > 1 ? 'boxes' : "box")
                ));
            }
        }
    }
}
