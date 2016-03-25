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

use RuntimeException;
use crodas\SimpleView\FixCode;
use crodas\FileUtil\File;

class Builder
{
    protected $functions = array();
    protected $map = array();
    protected $ns;
    protected $classes = [];
    protected static $count = 0;

    public function hasRules($class, $property)
    {
        $key = strtolower($class) . '::' . $property;
        return !empty($this->map[$key]) && 
            $this->functions[$this->map[$key]]->hasRules();
    }

    public function functionName($class, $property)
    {
        $key = strtolower($class) . '::' . $property;
        if (empty($this->map[$key])) {
            throw new RuntimeException("Cannot find validator funciton for $class::$property");
        }

        return $this->map[$key];
    }

    public function createTest($name)
    {
        $fnc = "validate_" . (++self::$count);
        $this->map[$name] = $fnc;
        $this->functions[$fnc] = new ValidateFunction($this);
        return $this->functions[$fnc];
    }

    public function setNamespace($ns)
    {
        if ($ns !== NULL && !preg_match('/^([a-z][a-z0-9_]*\\\\?)+$/i', $ns)) {
            throw new \RuntimeException("{$ns} is not a valid namespace");
        }
        $this->ns = $ns;

        return $this;
    }

    public function rule($name, Array $args = [], $msg = '')
    {
        $class = __NAMESPACE__ . "\\Rule\\" . ucfirst($name);
        if (class_exists($class)) {
            return new $class($name, $args, $msg);
        }
        $class = __NAMESPACE__ . "\\Rule\\" . ucfirst($name) . "Rule";
        if (class_exists($class)) {
            return new $class($name, $args, $msg);
        }
        return new Rule($name, $args, $msg);
    }

    public function getNamespace()
    {
        return $this->ns;
    }

    public function getVariables()
    {
        $var       = '$var_' . (++self::$count);
        $funcmap   = $this->map;
        $functions = $this->functions;
        $namespace = $this->ns;
        $classes   = $this->classes;

        foreach ($this->functions as $id => $function) {
            if (!$function->hasRules()) {
                $pos = array_search($id, $funcmap);
                unset($funcmap[$pos]);
                unset($functions[$id]);
            }
        }

        return compact(
            'namespace','funcmap', 'classes',
            'body', 'name', 'var', 'functions'
        );
    }

    public function getCode()
    {

        $code = Templates::get('body')
            ->render($this->getVariables(), true);
        
        if (class_exists('crodas\SimpleView\FixCode')) {
            return FixCode::fix($code);
        }

        return $code;
    }

    public function mapClass(Array $map)
    {
        foreach ($map as $name => $class) {
            if (Empty($class['props']) || !is_array($class['props'])) {
                continue;
            }
            $this->classes[$name] = $class['props'];
        }
        return $this;
    }

    public function writeTo($file)
    {
        return File::write($file, $this->GetCode());
    }
}
