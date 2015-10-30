<?php

namespace App\Command\Vagrant;

use App\Command;
use App\Service\Vagrant;
use App\Command\VagrantCommand;

class SuspendCommand extends VagrantCommand
{
    var $type = "suspend";
    var $action = "suspend";
}
