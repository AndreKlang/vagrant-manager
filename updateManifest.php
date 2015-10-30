<?php


$file = $argv[1];
$add = $argv[2];
$json = json_decode(file_get_contents($file));
$json[] = json_decode($add);

file_put_contents($file, json_encode($json,JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES));
