<?php

namespace crodas\Validator;

use crodas\FileUtil\File;
use WatchFiles\Watch;
use Notoj\Notoj;
use Notoj\ReflectionClass;

class Runtime extends BaseValidator
{
    protected $className;
    protected $callback;
    protected $temp;
    protected $prod;

    public function __construct($className, $temporary = null)
    {
        $this->className  = $className;
        $this->temp = $temporary ? $temporary : File::generateFilepath('validator', $className);
        $this->prod = !(defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE);
    }

    public function getAnnotations()
    {
        return [new ReflectionClass($this->className)];
    }
}
