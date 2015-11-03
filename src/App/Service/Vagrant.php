<?php

namespace App\Service;

use App\Service;
use App\Service\Vagrant\Host;

class Vagrant extends Service {

    public function getAllHosts($useCache = true) {

        # check if we have a previous result (and if we want to use it)
        if($useCache && ($current = self::fetch("all_hosts")) !== null) return $current;

        $shell = new Shell();
        $status = $shell->cmd("vagrant global-status");

        $hosts = array();
        foreach($status->output as $row){

            # replace tabs with spaces (both are used in the table)
            $row = str_replace("    "," ",$row);

            # explode on spaces
            $parts = explode(" ",$row);

            # on "host"-rows the ID (first column) is always 7 chars [a-z0-9]+
            if(strlen($parts[0]) !== 7) continue;

            # remove empty parts
            $parts = array_filter($parts);
            $parts = array_values($parts); // reset keys

            # make it a real host
            $host = new Host();
            $host->setData("id", $parts[0])
                ->setData("name", $parts[1])
                ->setData("provider", $parts[2])
                ->setData("state", $parts[3])
                ->setData("dir", $parts[4]);

            # add it to the list
            $hosts[] = $host;
        }

        # save it to store (for faster repeated fetching)
        self::store("all_hosts",$hosts);

        return $hosts;
    }

    /**
     * @param null $host
     * @return null|boolean
     */
    public function commandUp($host = null){
        $shell = new Shell();
        if($host === null) $result = $shell->start("vagrant up");
        else $result = $shell->start("vagrant up ".$host);

        return $result;
    }

    /**
     * @param null $host
     * @return null|boolean
     */
    public function commandHalt($host = null){
        $shell = new Shell();
        if($host === null) $result = $shell->start("vagrant halt");
        else $result = $shell->start("vagrant halt ".$host);

        return $result;
    }

    /**
     * @param null $host
     * @return null|boolean
     */
    public function commandDestroy($host = null){
        $shell = new Shell();
        if($host === null) $result = $shell->start("vagrant destroy");
        else $result = $shell->start("vagrant destroy ".$host);

        return $result;
    }

    /**
     * @param null $host
     * @return null|boolean
     */
    public function commandSuspend($host = null){
        $shell = new Shell();
        if($host === null) $result = $shell->start("vagrant suspend");
        else $result = $shell->start("vagrant suspend ".$host);

        return $result;
    }

    /**
     * @param null $host
     * @return null|boolean
     */
    public function commandSsh($host = null){
        $shell = new Shell();
        if($host === null) $result = $shell->start("vagrant ssh");
        else $result = $shell->start("vagrant ssh ".$host);

        return $result;
    }

    public function lookupBox($id){

        $return = null;
        if($id !== null){

            $allHosts = $this->getAllHosts();

            if(strlen($id)==7){
                $found = false;
                foreach($allHosts as $host){
                    if($host->getData("id") == $id){
                        $return = $host;
                        $found = true;
                        break;
                    }
                }
                if(!$found) throw new \InvalidArgumentException("Box with that ID is not found");
            }
            elseif(is_numeric($id)){
                if(!isset($allHosts[$id-1])) throw new \InvalidArgumentException("Box with that ID is not found");
                $return = $allHosts[$id-1];
            } else {
                throw new \InvalidArgumentException("Invalid argument");
            }
        }

        return $return;
    }

    public function resolveStr($str){

        $allHosts = $this->getAllHosts();

        # if the string is a "hash"
        if(preg_match('/^[a-z0-9]{7}$/',$str)){
            foreach($allHosts as $key => $host){
                if($host->getData("id") == $str) return [$key+1];
            }
            throw new \InvalidArgumentException("Box with that ID is not found");
        }

        $matches = [];

        $numbers = range(1,count($allHosts)); // all possible numbers

        $parts = explode(",",$str);
        foreach($parts as $part){
            $part = str_replace(" ","",$part);

            # go throgh the rules
            if($part == "*"){
                # if * (match all)
                $matches = $numbers;
            }
            elseif(substr($part,0,1)=="-"){
                # if it starts with a - (exclude that one)
                $key = array_search(substr($part,1),$matches);
                if(isset($matches[$key])) unset($matches[$key]);
            }
            elseif(substr($part,-1,1)=="-"){
                # if it ends with a - (take that one, and all after)
                foreach(range(substr($part,0,-1),count($allHosts)) as $match){
                    if(!in_array($match,$matches)) $matches[] = $match;
                }
            }
            elseif(preg_match("/([0-9]+)-([0-9]+)/",$part,$partNumbers)){
                # if it is a range 1-4 (include them and all in between)
                foreach(range($partNumbers[1],$partNumbers[2]) as $match){
                    if(!in_array($match,$matches)) $matches[] = $match;
                }
            }
            elseif(preg_match("/([0-9]+)/",$part,$partNumbers)){
                if(!in_array($partNumbers[1],$matches)) $matches[] = $partNumbers[1];
            }

        }

        # sanitize the result
        foreach($matches as $key => $match){
            if(!in_array($match,$numbers)) unset($matches[$key]);
        }

        return $matches;
    }

}