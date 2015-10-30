#!/bin/bash
if [ ! -f ./box.phar ]; then
    curl -LSs http://box-project.github.io/box2/installer.php | php
fi

./box.phar build

mv vagma.phar vagma