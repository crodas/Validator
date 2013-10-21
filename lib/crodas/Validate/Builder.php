<?php
/*
  +---------------------------------------------------------------------------------+
  | Copyright (c) 2013 Validate                                                     |
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
namespace crodas\Validate;

use crodas\SimpleView\FixCode;
use crodas\File;

class Builder
{
    protected $functions;
    protected $map;
    protected $ns;
    protected $classes = [];

    public function createTest($name)
    {
        $fnc = "validate_" . sha1($name);
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
        return new Rule($name, $args, $msg);
    }

    public function __toString()
    {
        $var       = '$var_' . uniqid(true);
        $funcmap   = $this->map;
        $functions = $this->functions;
        $namespace = $this->ns;
        $classes   = $this->classes;
        $code      = Templates::get('body')
            ->render(compact(
                'namespace','funcmap', 'classes',
                'body', 'name', 'var', 'functions'
            ), true);

        return FixCode::fix($code);
    }

    public function mapClass(Array $map)
    {
        foreach ($map as $name => $class) {
            if (Empty($class['props']) || !is_array($class['props'])) {
                throw new \Exception("Invalid class map for $name");
            }
            $this->classes[$name] = $class['props'];
        }
        return $this;
    }

    public function writeTo($file)
    {
        return File::write($file, (string)$this);
    }
}
