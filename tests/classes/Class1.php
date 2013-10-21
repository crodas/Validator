<?php

/** @Validate */
class Class1
{
    /** 
     * @Optional 
     * @noWhitespace("I cannot have whitespace") 
     * @MinLength([5], 'Too short') 
     * @MaxLength([10], 'Too long')
     */
    public $foo;

    /** @AnyOf([
     *      @Between([18, 25]), 
     *      @Between([30, 50])
     *  ], "Please provide something useful")
     */
    public $age;

    /** @Not([@Between([5, 99])], "test {$value} is invalid") */
    protected $test = 198;

    public function setTest($i)
    {
        $this->test = $i;
    }
}