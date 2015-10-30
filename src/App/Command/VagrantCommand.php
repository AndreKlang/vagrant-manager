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
            ->setHelp("
<fg=yellow>Matching rules:</>
This argument is called <info>identiyer</info> and it can take a set of rules:
    <info>*</info>              Match all boxes
    <info>2</info>              Match box 2
    <info>1-5</info>            Match boxes 1 through 5
    <info>-4</info>             Exclude box 4
    <info>4-</info>             Match box 4 and higher

Rules can also be combined, separated with a comma (,)
    <info>*,-3</info>           Match all boxes except 3
    <info>1,4-8,-6,12-</info>   Match boxes 1,4,5,7,8,12,13,14,,999

    <info>Note:</info> When combining rules, order does matter since the rules are processed in order
    <info>Note:</info> If you use just *, it will give you unexpecte result. Since
          that will just match the files in the current folder and send that as the argument.
          If you want to math all, use \"*\" instead (with quotes) or use ".$this->type.":all instead")

        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output){

        if($input->getOption("browse")){

            # print the status list
            $command = $this->getApplication()->find('status');
            $statusInput = new ArrayInput(array(
                "--slim"
            ));
            $command->run($statusInput, $output);

            # ask with boxes to handle
            $helper = $this->getHelper('question');
            $question = new Question('<fg=yellow>'."Select box/boxes to ".$this->action.':</> ',null);
            $question->setMaxAttempts(5);

            # set up validation for the question
            $question->setValidator(function ($answer) {
                $vagrant = new Vagrant();

                # check if the answer can be resolved
                if (!count($vagrant->resolveStr($answer))) {
                    throw new \RuntimeException(
                        'Your selection does not match any boxes'
                    );
                }
                return $answer;
            });

            # if we have an answer, set it as an argument, and move on
            if ($answer = $helper->ask($input, $output, $question)) {
                $input->setArgument("identifyer",$answer);
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $vagrant = new Vagrant();

        // TODO: Refactor this!
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

            # handle all boxes that match the inputStr
            foreach($vagrant->resolveStr($inputStr) as $id){

                /** @var \App\Service\Vagrant\Host $host */
                $host = $vagrant->lookupBox($id);

                # check if this box should be ignored
                if($host->getData("state")==$typeConf[$this->type]["skipIfState"]) continue;

                $i++;
                $id = $host->getData("id");

                $output->writeln(sprintf(
                    "<fg=yellow>".$typeConf[$this->type]["statement_head"].":</> %s <fg=blue>%s</>",
                    $host->getData("name"),
                    $host->getData("dir")
                ));

                $typeConf[$this->type]["command"]($id);
            }

            # print success-message
            if($i){
                $output->writeln(sprintf(
                    "<fg=green>Done:</> ".$typeConf[$this->type]["statement_foot"]." <fg=blue>%s</> %s",
                    $i,
                    ($i > 1 ? 'boxes' : "box")
                ));
            }
        }
    }
}
