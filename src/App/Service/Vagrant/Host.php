<?php

namespace App\Service\Vagrant;

use App\Service\Vagrant;

class Host extends Vagrant {

    private $_data = array(
        "id" => '',
        "name" => '',
        "provider" => '',
        "state" => '',
        "dir" => ''
    );

    /**
     * Set a value
     * @param $key
     * @param $value
     * @return $this
     */
    public function setData($key, $value){
        $this->_data[$key] = $value;
        return $this;
    }

    /**
     * Get a value
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function getData($key, $default = null){
        if(isset($this->_data[$key])) return $this->_data[$key];
        else return $default;
    }

    public function __toString(){
        return json_encode($this->_data,JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES);
    }

}