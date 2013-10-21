<?php

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../tests/classes/Class1.php";

function get_validator()
{
    static $val;
    if (empty($val)) {
        $val = new crodas\Validate\Init(__DIR__ . "/classes/", __DIR__ . "/tmp/foo.php");
    }
    return $val;
}
