<?php

use ActiveMongo2\Tests\Document\PostNoTitleDocument;

use test\FormValidator as f;

class GenerateTest extends \phpunit_framework_testcase
{
    public function testAnnotation1()
    {
        $val = get_validator();
        $foo = new Class1;
        $foo->age = 44;
        $errors = $val->validate($foo);
        $this->AssertTrue(empty($errors));
    }

    public function testAnnotation2()
    {
        $val = get_validator();
        $foo = new Class1;
        $foo->age  = 33;
        $foo->foo  = 'something';
        $foo->setTest(rand(5, 99));
        $errors = $val->validate($foo);
        $this->assertTrue(!empty($errors));
        $this->assertTrue(!empty($errors['test']));
        $this->assertTrue(strpos($errors['test']->getMessage(), 'invalid') > 0);
        $this->assertEquals(count($errors), 1);
    }

    public function testAnnotationAndDates()
    {
        $val = get_validator();
        $foo = new Class1;
        $foo->foo  = 'something';
        $foo->age  = 19;
        $foo->date = "30-09-2013";
        $errors = $val->validate($foo);
        $this->assertTrue(!empty($errors));
        $this->assertTrue(!empty($errors['date']));
        $this->assertTrue(strpos($errors['date']->getMessage(), 'format') > 0);
        $this->assertEquals(count($errors), 1);

        $foo->date     = NULL;
        $foo->any_date = "30-09-2013";
        $errors = $val->validate($foo);
        $this->assertTrue(empty($errors));
    }

    public function testAnnotationAndBool()
    {
        $val = get_validator();
        $foo = new Class1;
        $foo->foo  = 'something';
        $foo->age  = 19;
        $foo->enabled = "yes";
        $errors = $val->validate($foo);
        $this->assertTrue(!empty($errors));
        $this->assertTrue(!empty($errors['enabled']));

        $foo->enabled = false;
        $errors = $val->validate($foo);
        $this->assertTrue(empty($errors));

        $foo->enabled = true;
        $errors = $val->validate($foo);
        $this->assertTrue(empty($errors));
    }

    public function testAnnotationRaw()
    {
        $val = get_validator();
        $errors = $val->validateArray([
            'test' => rand(5, 99),
            'foo'  => 'something',
        ], 'Class1');
        $this->assertTrue(!empty($errors));
        $this->assertTrue(!empty($errors['test']));
        $this->assertTrue(strpos($errors['test']->getMessage(), 'invalid') > 0);
        $this->assertEquals(count($errors), 1);
    }

    public function testGenerate()
    {
        $validator = new crodas\Validator\Builder;
        $validator->setNamespace("test\\formValidator");
        
        $rules = $validator->createTest('negative')
            ->addRule('not', function($q) {
                $q->addRule('between', [2,30]);
                $q->addRule('between', [39,44]);
            });
        
        $rules = $validator->createTest('username')
            ->addRule('noWhitespace', [], 'Whitespaces are not allowed')
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
        $this->assertFalse(f\validate('username', 'crodasssssssssssssssss'));
    }

    /**
     *  @dependsOn testGenerate 
     *  @expectedException UnexpectedValueException
     *  @expectedExceptionMessage Whitespace
     */
    public function testException()
    {
        f\validate('username', 's here');
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
