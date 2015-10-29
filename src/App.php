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
}