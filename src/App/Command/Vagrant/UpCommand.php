<?php

namespace App\Command\Vagrant;

use App\Command;
use App\Service\Vagrant;
use App\Command\VagrantCommand;

class UpCommand extends VagrantCommand
{
    var $type = "up";
    var $action = "start";
}
