<?php

namespace App\Command\Vagrant;

use App\Command;
use App\Service\Vagrant;
use App\Command\VagrantCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HaltCommand extends VagrantCommand
{
    var $type = "halt";
    var $action = "stop";

    /**
     * {@inheritdoc}
     */
    public function getHostList(InputInterface $input, OutputInterface $output){

        $list = parent::getHostList($input, $output);
        if(is_array($list)) {

            foreach($list as $key => $host){
                /** @var \App\Service\Vagrant\Host $host */

                # remove "poweroff" from the list
                if($host->getData("state") == 'poweroff') unset($list[$key]);
            }
        }

        return $list;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $vagrant = new Vagrant();

        $hostList = $this->getHostList($input, $output);

        if($hostList === null){
            $vagrant->commandHalt();
        } else {
            $count = count($hostList);

            # handle all boxes that match the inputStr
            foreach($hostList as $host){
                /** @var \App\Service\Vagrant\Host $host */;

                $output->writeln(sprintf("<fg=yellow>Halting:</> %s <fg=blue>%s</>",
                    $host->getData("name"),$host->getData("dir")
                ));

                $vagrant->commandHalt($host->getData("id"));
            }

            # print success-message
            if($count){
                $output->writeln(sprintf("<fg=green>Done:</> Halted <fg=blue>%s</> %s",
                    $count,($count > 1 ? 'boxes' : "box")
                ));
            }
        }
    }
}
