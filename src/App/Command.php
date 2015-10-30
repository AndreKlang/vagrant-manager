<?php

namespace App;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\Vagrant;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Question\Question;

class Command extends \Symfony\Component\Console\Command\Command {

    /**
     * Placeholder to add "pre-command"-logic
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output){
    }

    function browseInteraction(InputInterface $input, OutputInterface $output, $text = 'Select box'){

    }

    function vagrantProcessCommand(InputInterface $input, OutputInterface $output, $type){


    }


}