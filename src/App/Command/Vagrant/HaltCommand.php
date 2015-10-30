<?php

namespace App\Command\Vagrant;

use App\Command;
use App\Service\Vagrant;
use App\Command\VagrantCommand;

class HaltCommand extends VagrantCommand
{
    var $type = "halt";
    var $action = "stop";
}
