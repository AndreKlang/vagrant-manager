#!/usr/bin/env bash

# use this to run the travis scripts locally


cd ..

php vendor/bin/phpcs --colors --standard=.travis/phpcs-ruleset.xml -n src/

php vendor/bin/phpmd src/ text naming,codesize,design,unusedcode

php vendor/bin/phpcpd src/