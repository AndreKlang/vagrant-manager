<?php

namespace Klang\App\Service;

use Klang\App\Service;

class Shell extends Service {

    /**
     * Call a command in the system
     * Possible abstraction layer..
     * @param $command
     * @return \Klang\App\Service\Shell\Response
     */
    public function cmd($command) {

        /** @var \Klang\App\Service\Shell\Response $response */
        $response = new Service\Shell\Response();

        exec($command, $response->output, $response->exitCode);

        return $response;
    }

    public function start($command){
        passthru($command, $return);
        return $return;
    }
}
