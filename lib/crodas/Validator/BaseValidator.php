<?php
/*
  +---------------------------------------------------------------------------------+
  | Copyright (c) 2015 Validator                                                     |
  +---------------------------------------------------------------------------------+
  | Redistribution and use in source and binary forms, with or without              |
  | modification, are permitted provided that the following conditions are met:     |
  | 1. Redistributions of source code must retain the above copyright               |
  |    notice, this list of conditions and the following disclaimer.                |
  |                                                                                 |
  | 2. Redistributions in binary form must reproduce the above copyright            |
  |    notice, this list of conditions and the following disclaimer in the          |
  |    documentation and/or other materials provided with the distribution.         |
  |                                                                                 |
  | 3. All advertising materials mentioning features or use of this software        |
  |    must display the following acknowledgement:                                  |
  |    This product includes software developed by César D. Rodas.                  |
  |                                                                                 |
  | 4. Neither the name of the César D. Rodas nor the                               |
  |    names of its contributors may be used to endorse or promote products         |
  |    derived from this software without specific prior written permission.        |
  |                                                                                 |
  | THIS SOFTWARE IS PROVIDED BY CÉSAR D. RODAS ''AS IS'' AND ANY                   |
  | EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED       |
  | WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE          |
  | DISCLAIMED. IN NO EVENT SHALL CÉSAR D. RODAS BE LIABLE FOR ANY                  |
  | DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES      |
  | (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;    |
  | LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND     |
  | ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT      |
  | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS   |
  | SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE                     |
  +---------------------------------------------------------------------------------+
  | Authors: César Rodas <crodas@php.net>                                           |
  +---------------------------------------------------------------------------------+
*/
namespace crodas\Validator;

use Notoj\Annotation\Base as AnnotationBase;
use Notoj\Annotation\Annotation;
use WatchFiles\Watch;

abstract class BaseValidator
{
    protected $callback;
    protected $mcallback;
    protected $temp;
    protected $dirs;
    protected $files;

    protected function load()
    {
        if (!is_callable($this->callback)) {
            $cache = new Watch($this->temp . '.cache');
            if (!$cache->isWatching() || ($this->prod || $cache->hasChanged())) {
                $builder = $this->getValidatorCode();
                $builder->writeTo($this->temp);
            }
            $data = require $this->temp;
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
            $cache->watchFiles($this->files);
            $cache->watchDirs($this->dirs);
            $cache->watch();
        }
    }

    protected function getAnnotationArgs(Annotation $annotation)
    {
        $args = $annotation->getArgs();
        if (empty($args[0])) {
            $args[0] = array();
        }
        if (empty($args[1])) {
            $args[1] = NULL;
        }
        if (is_string($args[0])) {
            $args[1] = $args[0];
            $args[0] = array();
        }

        return $args;
    }

    protected function getValidatorCode()
    {
        $builder = new Builder; 
        $classes = [];
        $files   = $dirs = [];
        foreach ($this->getAnnotations() as $class) {
            $props = array();
            foreach ($class->getProperties() as $property) {
                $props[$property->GetName()] = $property->isPublic();
                $name = strtolower($class->getName()) . '::' . $property->getName();
                $validator = $builder->createTest($name);
                foreach ($property->GetAnnotations() as $ann) {
                    if ($validator->ruleExists($ann->getName())) {
                        $args = $this->getAnnotationARgs($ann);
                        $args[0] = (array)$args[0];
                        foreach ($args[0] as &$param) {
                            if ($param instanceof Annotation) {
                                $xargs = $this->getAnnotationArgs($param);
                                $param = $builder->rule($param->getName(), $xargs[0], $xargs[1]);
                            }
                        }
                        $validator->AddRule($ann->GetName(), $args[0], $args[1]);
                    }
                }
            }
            $file = is_callable(array($class, 'getfile')) ? $class->getFile()  : $class->getFileName();

            $classes[$class->getName()] = array(
                'file' => $file,
                'props' => $props
            );
            $files[] = $file;
            $dirs[]  = dirname($file);

        }

        $builder->mapClass($classes);
        $this->files = $files;
        $this->dirs  = $dirs;

        return $builder;
    }

    public function generate()
    {
        return $this->load();
    }

    public function validate($object)
    {
        $this->load();
        $callback = $this->mcallback;
        return $this->validateArray($callback($object), get_class($object));
    }

    public function validateArray(Array $data, $type)
    {
        $this->load();
        $callback = $this->callback;
        $errors   = [];
        $type     = strtolower($type);
        foreach ($data as $key => $value) {
            try {
                if (!$callback("$type::$key", $value)) {
                    $errors[$key] = new \UnexpectedValueException;
                }
            } catch (\UnexpectedValueException $e) {
                $errors[$key] = $e;
            }
        }
        return $errors;
    }

    abstract protected function getAnnotations(); 

}
