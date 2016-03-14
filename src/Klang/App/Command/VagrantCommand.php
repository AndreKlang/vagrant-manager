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

class VagrantCommand extends Command
{
    public $type = ""; // the name of the command, like "up"
    public $action = ""; // the "word", like "start"

    protected function configure(){
        $this
            ->setName($this->type)
            ->setDescription(ucfirst($this->action).' box')
            ->addArgument(
                'identifier',
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
          If you want to math all, use \"*\" (with quotes), or use ".$this->type.":all instead")
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output){

        if($input->hasOption("browse") && $input->getOption("browse")) {

            # print the status list
            $command = $this->getApplication()->find('status');
            $statusInput = new ArrayInput(array(), $command->getDefinition());
            $command->run($statusInput, $output);

            # ask with boxes to handle
            $helper = $this->getHelper('question');
            $question = new Question('<fg=yellow>'."Select box/boxes to ".$this->action.':</> ', null);
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
                $input->setArgument("identifier", $answer);
            }
        }
    }

    /**
     * Should return (one of)
     *      array of \Klang\App\Service\Vagrant\Host
     *      null, if no input is given (empty identifier)
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null|array(\Klang\App\Service\Vagrant\Host)
     */
    public function getHostList(InputInterface $input, OutputInterface $output){

        $vagrant = new Vagrant();

        $inputStr = $input->getArgument("identifier");

        if($inputStr === null) {
            return null;
        } else {

            $hosts = [];
            # handle all boxes that match the inputStr
            foreach($vagrant->resolveStr($inputStr) as $id) {

                /** @var \Klang\App\Service\Vagrant\Host $host */
                $host = $vagrant->lookupBox($id);
                $hosts[] = $host;
                unset($host);
            }
            return $hosts;
        }
    }
}
