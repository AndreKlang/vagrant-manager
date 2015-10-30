<?php

namespace App\Command\Vagrant;

use App\Command;
use App\Service\Vagrant;
use App\Command\VagrantCommand;

class RestartCommand extends VagrantCommand
{
    var $type = "restart";
    var $action = "restart";
}
