#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use Klang\App;
use Klang\App\Command;

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

$application->add(new Command\Vagrant\SshCommand());

$application->add(new Command\MultiCommand());

$application->add(new Command\SelfupdateCommand());

$application->add(new Command\HelpCommand());
$application->add(new Command\AboutCommand());

$application->run();
