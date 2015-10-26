#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use App\Command;

$application = new App('Vagrant Manager', '@package_version@');
$application->add(new Command\HelloCommand());
$application->run();
