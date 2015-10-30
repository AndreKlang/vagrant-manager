<?php

namespace App\Command;

use App\Command;
use App\Service\Vagrant;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Question\Question;

class HelloCommand extends Command
{
    protected function configure(){
        $this
            ->setName('hello')
            ->setDescription('Say hello')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $output->writeln('Hello World');

        $vagrant = new Vagrant();
        //$output->writeln($vagrant->getAllHosts());

        print_r($vagrant->resolveStr("2- , -5 , 1 - 4"));
        print_r($vagrant->resolveStr("1 , 2 , 3 , 5 - "));
        print_r($vagrant->resolveStr("10"));
    }
}
