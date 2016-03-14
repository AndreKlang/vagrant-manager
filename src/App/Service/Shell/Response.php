<?php

namespace App\Service\Shell;

use App\Service\Shell;

class Response extends Shell {

    var $exitCode = null;
    var $output = array();

    public function __toString(){
        return json_encode(array(
            "exitCode" => $this->exitCode,
            "output" => $this->output
        ), JSON_UNESCAPED_SLASHES + JSON_PRETTY_PRINT);
    }
}
