<?php

error_reporting(E_ALL);
ini_set('display_error', 'on');

require __DIR__ . "/vendor/autoload.php";

$cli = new crodas\cli\Cli(__DIR__ . '/vendor/cli.php');
$cli->addDirectory(__DIR__ . '/cli');
$cli->main();
