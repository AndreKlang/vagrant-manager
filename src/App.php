<?php

use Symfony\Component\Console\Application;

class App extends Application {

    private static $_registry = array();

    public static function store($key, $value){
        self::$_registry[$key] = $value;
    }

    public static function fetch($key, $default = null){
        if(isset(self::$_registry[$key])) return self::$_registry[$key];
        else return $default;
    }

    public function getLongVersion(){
        return parent::getLongVersion()."

<fg=yellow>Matching rules:</>
Most commands (halt, restart, suspend & up) supports an argument to select boxes.
This argument is called <info>identiyer</info> and it can take a set of rules:
    <info>*</info>              Match all boxes
    <info>2</info>              Match box 2
    <info>1-5</info>            Match boxes 1 through 5
    <info>-4</info>             Exclude box 4
    <info>4-</info>             Match box 4 and higher

Rules can also be combined, separated with a comma (,)
    <info>*,-3</info>           Match all boxes except 3
    <info>1,4-8,-6,12-</info>   Match boxes 1,4,5,7,8,12,13,14,,999

    <info>Note:</info> When combining rules, order does matter since the rules are processed in order";
    }
}