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
namespace crodas\Validator;

class ValidateFunction
{
    protected $rules = [];
    protected $parent;
    public $result;

    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    public function hasRules()
    {
        return !empty($this->rules);
    }

    public function ruleExists($name)
    {
        try {
            Templates::get($name);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function addRule($name, $args = [], $error = '')
    {
        if (is_callable($args)) {
            $tmp = new self($this->parent);
            $args($tmp);
            $args = $tmp->rules;
        }
        $this->rules[] = $this->parent->rule($name, $args, $error);
        return $this;
    }

    public function toCode($var)
    {
        if (empty($this->rules)) {
            $body = $this->parent->rule('allOf', []);
            $code = $body->toCode($var);
            return $code;
        }

        if (count($this->rules) > 1) {
            $body = $this->parent->rule('allOf', $this->rules);
        } else {
            $body = $this->rules[0];
        }
        $body = $this->parent->rule('allOf', $this->rules);
        $code = $body->toCode($var);
        $this->result = $body->result;

        return $code;
    }

}

