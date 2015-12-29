<?php

use crodas\FileUtil\File;

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../tests/classes/Class1.php";

foreach(glob(__DIR__ . '/tmp/*') as $file) {
    unlink($file);
}

date_default_timezone_set('America/Asuncion');

File::overrideFilepathGenerator(function($prefix) {
    return __DIR__ . '/tmp/';
});

function get_validator()
{
    static $val;
    if (empty($val)) {
        $val = new crodas\Validator\Init(__DIR__ . "/classes/", __DIR__ . "/tmp/foo.php");
    }
    return $val;
}
