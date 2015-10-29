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
            $greetInput = new ArrayInput(array());
            $command->run($greetInput, $output);

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


}