<?php

namespace App\Command;

use App\Command;
use App\Service\Vagrant;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StatusCommand extends Command
{
    protected function configure(){
        $this
            ->setName('status')
            ->setDescription('Get current status')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $vagrant = new Vagrant();

        $rows = array();
        foreach($vagrant->getAllHosts() as $key => $box){
            /** @var \App\Service\Vagrant\Host $box */

            if($box->getData("state") == "poweroff") $status = "<fg=red>Off</>";
            elseif($box->getData("state") == "saved") $status = "<fg=blue>Suspended</>";
            else $status = "<fg=green>On</>";

            $rows[] = array(
                $key + 1,
                $box->getData("name"),
                $box->getData("dir"),
                $status
            );
        }

        $table = $this->getHelper('table');
        $table
            ->setHeaders(array('#','Name', 'Dir', 'Status'))
            ->setRows($rows)
        ;
        $table->render($output);
    }
}
