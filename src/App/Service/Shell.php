<?php

namespace App\Service;

use App\Service;

class Shell extends Service {

    /**
     * Call a command in the system
     * Possible abstraction layer..
     * @param $command
     * @return \App\Service\Shell\Response
     */
    public function cmd($command) {

        /** @var \App\Service\Shell\Response $response */
        $response = new Service\Shell\Response();

        exec($command, $response->output, $response->exitCode);

        return $response;
    }

    public function start($command){
        passthru($command, $return);
        return $return;
    }
}
