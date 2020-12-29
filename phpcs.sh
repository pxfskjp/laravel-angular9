#!/bin/bash
./vendor/bin/phpcs --standard=PSR12 --report-width=200 --extensions=php --ignore=*.blade.php src tests -p

