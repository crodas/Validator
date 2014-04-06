<?php

/**
 *  @Cli("validator:generate")
 *  @Arg("file", REQUIRED)
 *  @Arg("dir", REQUIRED|IS_ARRAY)
 */
function create($in, $out)
{
    $init = new crodas\Validator\Init(
        current($in->getArgument('dir')),
        $in->getArgument('file'),
        true
    );
    $init->generate();
}
