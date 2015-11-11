<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\HelpCommand as StandardHelpCommand;

class HelpCommand extends StandardHelpCommand
{
    protected function configure(){
        parent::configure();

        $this->setHelp(<<<EOF
The <info>%command.name%</info> command displays help for a given command:

  <info>vagma help <command></info>

You can also output the help in other formats by using the <comment>--format</comment> option:

  <info>vagma help --format=xml list</info>

<bg=yellow;fg=black> Beginners => </> To display the list of available commands, please use the <info>list</info> command:

  <info>vagma list</info>

EOF
        );
    }
}
