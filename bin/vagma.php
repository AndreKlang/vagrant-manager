#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use App\Command;

$application = new App('Vagrant Manager', '@package_version@');

$application->add($status = new Command\StatusCommand());
$application->setDefaultCommand($status->getName());

$application->add(new Command\UpCommand());
$application->add(new Command\Up\AllCommand());

$application->add(new Command\SuspendCommand());
$application->add(new Command\Suspend\AllCommand());

$application->add(new Command\RestartCommand());
$application->add(new Command\Restart\AllCommand());

$application->add(new Command\HaltCommand());
$application->add(new Command\Halt\AllCommand());

# good for tests
# $application->add(new Command\HelloCommand());

$application->run();
