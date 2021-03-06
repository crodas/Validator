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

class Rule
{
    public    $result;
    public    $msg;
    protected $type;
    protected $args;
    protected $testScalar = true;
    protected static $inc = 0;

    public function __construct($type, Array $args, $msg='')
    {
        $this->result = '$is_' . $type . '_' . (self::$inc++);
        $this->type   = $type;
        $this->msg    = $msg;
        $this->args   = $args;
    }

    public function getErrorMessage()
    {
        if (!empty($this->msg)) {
            $all = array();
            $msg = preg_replace_callback('/(\{\$[a-zA-Z_0-9]+\})/', function($a) use (&$all) {
                $all[] = '$' . substr($a[0], 2, -1);
                return '%s';
            }, $this->msg);

            $msg = "_(" . var_export($msg, true) . ")";

            if (!empty($all)) {
                $msg = "sprintf($msg, ";
                foreach ($all as $key) {
                    $msg .= $key;
                }
                $msg .= ")";
            }

            return $msg;
        }
    }

    public function getWeight()
    {
        $weight = 10;
        foreach ($this->args as $input) {
            $weight += 1;
            if ($input instanceof self) {
                $weight += $input->getWeight();
            }
        }
        return $weight;
    }

    public function toCode($input, $parent = NULL)
    {
        $self   = $this;
        $args   = (array)$this->args;
        $type   = $this->type;
        $scalar = $this->testScalar;

        $this->msg = str_replace('$value', $input, $this->msg);
        $code  = "\n/* {$this->type} {{{ */\n";
        $code .= Templates::get('rule')
            ->render(compact('self', 'input', 'args', 'parent', 'type', 'scalar'), true);
        $code .= Templates::get('error')
            ->render(compact('self', 'parent'), true);
        $code .= "/* }}} */\n";

        return $code;
    }
}
