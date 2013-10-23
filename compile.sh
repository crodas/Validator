#!/bin/bash

cd `dirname $0`

php vendor/crodas/simple-view-engine/cli.php compile -N crodas\\Validator  $(pwd)/lib/crodas/Validator/Template $(pwd)/lib/crodas/Validator/Templates.php
