#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use App\Command;

$application = new App('Vagrant Manager', '@package_version@');
$application->add(new Command\HelloCommand());
$application->add(new Command\StatusCommand());
$application->add(new Command\UpCommand());
$application->add(new Command\HaltCommand());
$application->add(new Command\Halt\AllCommand());
$application->run();
