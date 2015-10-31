<?php

$manifest = file_get_contents("https://andreklang.github.io/vagrant-manager/manifest.json");
$manifest = json_decode($manifest);

$latest = array_pop($manifest);

file_put_contents("vagma",file_get_contents($latest->url));

exec("chmod +x vagma");
echo "vagrant-manager installed!\n";