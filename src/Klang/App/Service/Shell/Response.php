<?php

namespace Klang\App\Service\Shell;

use Klang\App\Service\Shell;

class Response extends Shell {

    public $exitCode = null;
    public $output = array();

    public function __toString(){
        return json_encode(array(
            "exitCode" => $this->exitCode,
            "output" => $this->output
        ), JSON_UNESCAPED_SLASHES + JSON_PRETTY_PRINT);
    }
}
