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
        if($input->getOption("browse")){

            $vagrant = new Vagrant();
            $allHosts = $vagrant->getAllHosts();


            $command = $this->getApplication()->find('status');
            $statusInput = new ArrayInput(array());
            $command->run($statusInput, $output);

            $helper = $this->getHelper('question');
            $question = new Question('<fg=yellow>'.$text.' [1-'.count($allHosts).']:</> ',null);
            $question->setValidator(function ($answer) {
                $vagrant = new Vagrant();
                $allHosts = $vagrant->getAllHosts();
                if (!is_numeric($answer) || $answer < 1 || $answer > count($allHosts)) {
                    throw new \RuntimeException(
                        'Invalid option'
                    );
                }
                return $answer;
            });
            $question->setMaxAttempts(5);

            if ($answer = $helper->ask($input, $output, $question)) {
                $input->setArgument("identifyer",$answer);
            }
        }
    }

    function vagrantProcessCommand(InputInterface $input, OutputInterface $output, $type){

        $vagrant = new Vagrant();

        $typeConf = array(
            "up" => array(
                "currentState" => "running",
                "command" => function($id){
                    $vagrant = new Vagrant();
                    $vagrant->commandUp($id);
                },
                "statement_head" => "Bringing up",
                "statement_foot" => "started"
            ),
            "halt" => array(
                "currentState" => "poweroff",
                "command" => function($id){
                    $vagrant = new Vagrant();
                    $vagrant->commandHalt($id);
                },
                "statement_head" => "Shuting down",
                "statement_foot" => "shut down"
            ),
            "suspend" => array(
                "currentState" => "saved",
                "command" => function($id){
                    $vagrant = new Vagrant();
                    $vagrant->commandSuspend($id);
                },
                "statement_head" => "Suspending",
                "statement_foot" => "suspended"
            ),
            "restart" => array(
                "currentState" => "does-not-matter",
                "command" => function($id){
                    $vagrant = new Vagrant();
                    $vagrant->commandHalt($id);
                    $vagrant->commandUp($id);
                },
                "statement_head" => "Restarting",
                "statement_foot" => "restarted"
            ),
            "destroy" => array(
                "currentState" => "does-not-matter",
                "command" => function($id){
                    $vagrant = new Vagrant();
                    $vagrant->commandDestroy($id);
                },
                "statement_head" => "Destroying",
                "statement_foot" => "destroyed"
            ),
        );

        if(!in_array($type,array_keys($typeConf))) throw new \InvalidArgumentException("Not a valid command type: ".$type);

        $inputStr = $input->getArgument("identifyer");

        if($inputStr === null){
            $vagrant->$typeConf[$type]["command"]();
        } else {
            $i = 0;
            foreach($vagrant->resolveStr($inputStr) as $id){
                $host = $vagrant->lookupBox($id);

                if($host->getData("state")==$typeConf[$type]["currentState"]) continue;

                $i++;

                $id = $host->getData("id");
                $output->writeln(sprintf("<fg=yellow>".$typeConf[$type]["statement_head"].":</> %s <fg=blue>%s</>",$host->getData("name"),$host->getData("dir")));

                $typeConf[$type]["command"]($id);
            }
            if($i) $output->writeln(sprintf("<fg=green>Done:</> ".$typeConf[$type]["statement_foot"]." <fg=blue>%s</> %s",$i,($i > 1 ? 'boxes' : "box")));
        }
    }


}