<?php

class Class2
{
    /** 
     * @Optional 
     * @noWhitespace("I cannot have whitespace") 
     * @MinLength([5], 'Too short') 
     * @MaxLength([10], 'Too long')
     */
    public $foo;
}
