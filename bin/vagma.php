#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use App\Command;

$application = new App('Vagrant Manager', '@package_version@');

$application->add($status = new Command\StatusCommand());
$application->setDefaultCommand($status->getName());

$application->add(new Command\Vagrant\UpCommand());
$application->add(new Command\Vagrant\Up\AllCommand());

$application->add(new Command\Vagrant\SuspendCommand());
$application->add(new Command\Vagrant\Suspend\AllCommand());

$application->add(new Command\Vagrant\RestartCommand());
$application->add(new Command\Vagrant\Restart\AllCommand());

$application->add(new Command\Vagrant\HaltCommand());
$application->add(new Command\Vagrant\Halt\AllCommand());

$application->add(new Command\SelfupdateCommand());

# good for tests
# $application->add(new Command\HelloCommand());

$application->run();
