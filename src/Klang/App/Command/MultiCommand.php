<?php

namespace Klang\App\Command;

use Klang\App\Command;
use Klang\App\Service\Vagrant;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Question\Question;

class MultiCommand extends Command
{
    private $actions = array(
        "stop" => array("action"=>"halt"),
        "restart" => array("action"=>"restart"),
        "suspend" => array("action"=>"suspend"),
        "start" => array("action"=>"up")
    );

    protected function configure(){
        $this
            ->setName("multi")
            ->setDescription("Perform multiple actions, on selected boxes")
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output){

        # print the status list
        /** @var StatusCommand $command */
        $command = $this->getApplication()->find('status');
        $statusInput = new ArrayInput(array(), $command->getDefinition());
        $command->run($statusInput, $output);

        foreach($this->actions as $name => $action) {
            # ask with boxes to handle
            $helper = $this->getHelper('question');
            $question = new Question('<fg=yellow>'."Select box/boxes to ".$name.' []:</> ', null);
            $question->setMaxAttempts(5);

            # set up validation for the question
            $question->setValidator(function ($answer) {
                $vagrant = new Vagrant();

                # check if the answer can be resolved
                if ($answer != "" && !count($vagrant->resolveStr($answer))) {
                    throw new \RuntimeException(
                        'Your selection does not match any boxes'
                    );
                }
                return $answer;
            });

            # if we have an answer, set it as an argument, and move on
            if ($answer = $helper->ask($input, $output, $question)) {
                $this->actions[$name]["boxes"] = $answer;
            }
        }

    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $vagrant = new Vagrant();

        foreach($this->actions as $name => $action) {
            if(!isset($action["boxes"])) {
                continue;
            }

            /** @var VagrantCommand $command */
            $command = $this->getApplication()->find($action["action"]);
            $statusInput = new ArrayInput(array(
                "identifier" => $action["boxes"]
            ), $command->getDefinition());
            $command->run($statusInput, $output);

            $vagrant->flushCache();
        }
    }
}
