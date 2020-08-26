#!/bin/bash
if [ -z "$1" ]; then
    vendor/bin/phpunit -c phpunit.xml
else
    vendor/bin/phpunit -c phpunit.xml --group $1
fi

