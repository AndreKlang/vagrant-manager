<?php

namespace App\Command\Vagrant;

use App\Command;
use App\Service\Vagrant;
use App\Command\VagrantCommand;

class SshCommand extends VagrantCommand
{
    var $type = "ssh";
    var $action = "ssh";
}
