<?php
/*
  +---------------------------------------------------------------------------------+
  | Copyright (c) 2013 Validator                                                     |
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

use Notoj\Dir as DirParser;
use WatchFiles\Watch;

class Init
{
    protected $dir;
    protected $temp;
    protected $prod;
    protected $ns;
    protected $callback;
    protected $mcallback;
    protected $files;
    protected $dirs;

    public function __construct($dir, $temporary, $prod = false)
    {
        $this->dir  = $dir;
        $this->temp = $temporary;
        $this->prod = $prod;
        $this->ns   = 'ns' . sha1($temporary);
        $this->callback  = $this->ns . "\\validate";
        $this->mcallback = $this->ns . "\\get_object_properties";
    }

    protected function getAnnotations()
    {
        $parser      = new DirParser($this->dir);
        $annotations = $parser->getAnnotations();
        return $annotations->get('Validate');
    }

    public function generate()
    {
        return $this->load();
    }

    protected function getValidatorCode()
    {
        $builder = new Builder; 
        $classes = [];
        $files   = $dirs = [];
        $builder->setNamespace($this->ns);
        foreach ($this->GetAnnotations() as $object) {
            if (!$object->isClass()) continue;
            $props = [];
            foreach ($object->getProperties() as $prop) {
                $name = strtolower($prop['class']) . '::' . $prop['property'];
                $validator = $builder->createTest($name);
                $props[$prop['property']] = in_array('public', $prop['visibility']);

                foreach ($prop->getAll() as $ann) {
                    if ($validator->ruleExists($ann['method'])) {
                        if (empty($ann['args'][0])) {
                            $ann['args'][0] = [];
                        } 
                        if (empty($ann['args'][1])) {
                            $ann['args'][1] = NULL;
                        } 
                        if (is_string($ann['args'][0])) {
                            $ann['args'][1] = $ann['args'][0];
                            $ann['args'][0] = [];
                        }
                        foreach ($ann['args'][0] as &$param) {
                            if ($param instanceof \Notoj\Annotation\Base) {
                                if (empty($param['args'][0])) {
                                     $param['args'][0] = [];
                                } 
                                if (empty($param['args'][1])) {
                                    $param['args'][1] = NULL;
                                } 
                                if (is_string($param['args'][0])) {
                                    $param['args'][1] = $param['args'][0];
                                    $param['args'][0] = [];
                                }
                                $param = $builder->rule($param['method'], (Array)$param['args'][0], $param['args'][1]);
                            }
                        }
                        $validator->addRule($ann['method'], (array)$ann['args'][0], $ann['args'][1]);
                    }
                }
                $classes[$object['class']] = ['file' => $object['file'], 'props' => $props];
                $files[] = $object['file'];
                $dirs[]  = dirname($object['file']);
            }
        }

        $builder->mapClass($classes);
        $this->files = $files;
        $this->dirs  = $dirs;

        return $builder;
    }

    protected function load()
    {
        if (is_callable($this->callback)) {
            return;
        }

        $cache = new Watch($this->temp . '.cache');
        if ($cache->isWatching() && ($this->prod || !$cache->hasChanged())) {
            require $this->temp;
            return;
        }

        $builder = $this->getValidatorCode();
        $builder->writeTo($this->temp);
        require $this->temp;

        $cache->watchFiles($this->files);
        $cache->watchDirs($this->dirs);
        $cache->watch();
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
}
