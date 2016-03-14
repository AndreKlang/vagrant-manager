<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;

class App extends Application {

    private static $_registry = array();

    public static function store($key, $value){
        self::$_registry[$key] = $value;
    }

    public static function fetch($key, $default = null){
        if(isset(self::$_registry[$key])) {
            return self::$_registry[$key];
        } else {
            return $default;
        }
    }

    /**
     * Disable some unused options
     * @return InputDefinition
     */
    protected function getDefaultInputDefinition() {
        return new InputDefinition(array(
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),

            new InputOption('--help', '-h', InputOption::VALUE_NONE, 'Display this help message'),
            //new InputOption('--quiet', '-q', InputOption::VALUE_NONE, 'Do not output any message'),
            //new InputOption('--verbose', '-v|vv|vvv', InputOption::VALUE_NONE, 'Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug'),
            new InputOption('--version', '-V', InputOption::VALUE_NONE, 'Display this application version'),
            //new InputOption('--ansi', '', InputOption::VALUE_NONE, 'Force ANSI output'),
            //new InputOption('--no-ansi', '', InputOption::VALUE_NONE, 'Disable ANSI output'),
            //new InputOption('--no-interaction', '-n', InputOption::VALUE_NONE, 'Do not ask any interactive question'),
        ));
    }
}
