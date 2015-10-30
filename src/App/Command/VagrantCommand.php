<?php

namespace App\Command;

use App\Command;
use App\Service\Vagrant;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Question\Question;

class VagrantCommand extends Command
{
    var $type = ""; // the name of the command, like "up"
    var $action = ""; // the "word", like "start"

    protected function configure(){
        $this
            ->setName($this->type)
            ->setDescription(ucfirst($this->action).' box')
            ->addArgument(
                'identifyer',
                InputArgument::OPTIONAL,
                'Select which box to '.$this->action.' (number, hash or "matching rules")'
            )
            ->addOption(
                'browse',
                'b',
                InputOption::VALUE_NONE,
                'Allows you to select one from a list'
            )
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output){

        if($input->getOption("browse")){

            $vagrant = new Vagrant();
            $allHosts = $vagrant->getAllHosts();


            $command = $this->getApplication()->find('status');
            $statusInput = new ArrayInput(array());
            $command->run($statusInput, $output);

            $helper = $this->getHelper('question');
            $question = new Question('<fg=yellow>'."Select box to ".$this->action.' [1-'.count($allHosts).']:</> ',null);
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

    protected function execute(InputInterface $input, OutputInterface $output){
        $vagrant = new Vagrant();

        $typeConf = array(
            "up" => array(
                "skipIfState" => "running",
                "command" => function($id){
                    $vagrant = new Vagrant();
                    $vagrant->commandUp($id);
                },
                "statement_head" => "Bringing up",
                "statement_foot" => "started"
            ),
            "halt" => array(
                "skipIfState" => "poweroff",
                "command" => function($id){
                    $vagrant = new Vagrant();
                    $vagrant->commandHalt($id);
                },
                "statement_head" => "Shuting down",
                "statement_foot" => "shut down"
            ),
            "suspend" => array(
                "skipIfState" => "saved",
                "command" => function($id){
                    $vagrant = new Vagrant();
                    $vagrant->commandSuspend($id);
                },
                "statement_head" => "Suspending",
                "statement_foot" => "suspended"
            ),
            "restart" => array(
                "skipIfState" => null,
                "command" => function($id){
                    $vagrant = new Vagrant();
                    $vagrant->commandHalt($id);
                    $vagrant->commandUp($id);
                },
                "statement_head" => "Restarting",
                "statement_foot" => "restarted"
            ),
            "destroy" => array(
                "skipIfState" => null,
                "command" => function($id){
                    $vagrant = new Vagrant();
                    $vagrant->commandDestroy($id);
                },
                "statement_head" => "Destroying",
                "statement_foot" => "destroyed"
            ),
        );

        if(!in_array($this->type,array_keys($typeConf))) throw new \InvalidArgumentException("Not a valid command type: ".$this->type);

        $inputStr = $input->getArgument("identifyer");

        if($inputStr === null){
            $typeConf[$this->type]["command"](null);
        } else {
            $i = 0;
            foreach($vagrant->resolveStr($inputStr) as $id){
                $host = $vagrant->lookupBox($id);

                if($host->getData("state")==$typeConf[$this->type]["skipIfState"]) continue;

                $i++;

                $id = $host->getData("id");
                $output->writeln(sprintf("<fg=yellow>".$typeConf[$this->type]["statement_head"].":</> %s <fg=blue>%s</>",$host->getData("name"),$host->getData("dir")));

                $typeConf[$this->type]["command"]($id);
            }
            if($i) $output->writeln(sprintf("<fg=green>Done:</> ".$typeConf[$this->type]["statement_foot"]." <fg=blue>%s</> %s",$i,($i > 1 ? 'boxes' : "box")));
        }
    }
}
