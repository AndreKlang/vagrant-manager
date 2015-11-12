<?php

namespace App\Command;

use App\Command;
use App\Service\Vagrant;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;

class StatusCommand extends Command
{
    protected function configure(){
        $this
            ->setName('status')
            ->setDescription('Get current status')
            ->addOption(
                'refresh',
                'r',
                InputOption::VALUE_NONE,
                'Refresh the status of your boxes'
            )
            ->setHelp(
                "This will show you a pretty table of all your boxes, and their current state."."\n".
                "\n".
                "<bg=red>Note:</> This list is cached on your system"."\n".
                "   If you find that the list is not correct, run the status again with the <info>--refresh</info> option"."\n"
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $vagrant = new Vagrant();

        $rows = array();
        foreach($vagrant->getAllHosts($input->getOption("refresh")) as $key => $box){
            /** @var \App\Service\Vagrant\Host $box */

            if($box->getData("state") == "poweroff") $status = "<fg=red>Off</>";
            elseif($box->getData("state") == "saved") $status = "<fg=blue>Suspended</>";
            elseif($box->getData("state") == "running")  $status = "<fg=green>On</>";
            elseif($box->getData("state") == "aborted")  $status = "<fg=red>Aborted</>";
            else  $status = "<bg=red> Unknown </>";

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
