<?php

use ActiveMongo2\Tests\Document\PostNoTitleDocument;

use test\FormValidator as f;

class GenerateTest extends \phpunit_framework_testcase
{
    public function testGenerate()
    {
        $validator = new crodas\Validate\Builder;
        $validator->setNamespace("test\\formValidator");
        
        $rules = $validator->createTest('negative')
            ->addRule('not', function($q) {
                $q->addRule('between', [2,30]);
                $q->addRule('between', [39,44]);
            });
        
        $rules = $validator->createTest('username')
            ->addRule('noWhitespace', [], 'Whitespaces are not allowed')
            ->addRule('alnum')
            ->addRule('length', [5, 15]);
        
        $rules = $validator->createTest('between')
            ->addRule('between', [1, 99]);
        
        $rules = $validator->createTest('email')
            ->addRule('optional')
            ->addRule('email');

        $validator->writeTo(__DIR__ . '/tmp/test1.php');
        $this->assertFalse(is_callable('test\\FormValidator\\validate'));
        require __DIR__ . '/tmp/test1.php';
        $this->assertTrue(is_callable('test\\FormValidator\\validate'));
    }

    /** @dependsOn testGenerate */
    public function testNegative()
    {
        $this->assertTrue(f\validate('negative', 99));
        $this->assertFalse(f\validate('negative', 2));
        $this->assertFalse(f\validate('negative', 44));
    }

    /** @dependsOn testGenerate */
    public function testUsername()
    {
        $this->assertTrue(f\validate('username', 'crodas'));
        $this->assertFalse(f\validate('username', 'cro'));
        $this->assertFalse(f\validate('username', 'crodas!'));
    }

    /**
     *  @dependsOn testGenerate 
     *  @expectsException \InvalidArgumentException
     */
    public function testException()
    {
        f\validate('username', 'something here');
    }

    /**
     *  @dependsOn testGenerate 
     */
    public function testEmailAndOptional()
    {
        $this->assertTrue(f\validate('email', 'crodas@php.net'));
        $this->assertTrue(f\validate('email', NULL));
        $this->assertFalse(f\validate('email', 'crodas@php'));
    }
}
