<?php

use test\All as f;

class AllTest extends \phpunit_framework_testcase
{
    protected static $args = [
        'between' => [0, 99],
        'min'       => [5],
        'max'       => [50],
        'length'    => [5,50],
        'minlength' => [5],
        'maxlength' => [50],
        'charset'   => ['ascii'],
        'regexp' => ['/[0-9]/'],
    ];
    
    protected static $dont = [
        'when' => 1, 'not' => 1, 'body' => 1, 'rule' => 1, 'error' => 1,
        'anyof' => 1, 'allof' => 1, 'not' => 1,
    ];

    public static function getAllRules()
    {
        $all = \crodas\Validator\Templates::getAll();
        $tpl = [];
        foreach ($all as $name) {
            if (!empty(self::$dont[$name])) {
                continue;
            }
            $tpl[] = $name;
        }
        return $tpl;
    }

    public static function provideAll()
    {
        $class = new \crodas\Validator\Builder;
        $class->setNamespace('test\All');
        $args  = [];
        foreach (self::getAllRules() as $name) {
            $args[] = [$class, $name, !empty(self::$args[$name]) ? self::$args[$name] : []];
        }
        $args[] = [$class, null, []];
        return $args;
    }

    /**
     *  @dataProvider provideAll
     */
    public function testCompile($builder, $name, $args)
    {
        if (empty($name)) {
            $file = __DIR__ . '/tmp/all.php';
            $builder->writeTo($file);
            $this->assertFalse(is_callable('test\All\validate'));
            require $file;
            $this->assertTrue(is_callable('test\All\validate'));
            return;
        }

        $builder->createTest('test_' . $name)
            ->addRule($name, $args);

        $builder->createTest('test_we_' . $name)
            ->addRule($name, $args, 'exception');

    }

    public static function provideTestInvalid()
    {
        return array_filter(self::provideTest(), function($v) {
            return !$v[2];
        });
    }

    public static function provideTest()
    {
        return [
            ['between', -55, false],
            ['between', rand(5, 99), true],
            ['between', 100, false],
            ['between', 100, false],

            ['min', -55, false],
            ['min', 155, true],
            ['max', -55, true],
            ['max', 155, false],

            ['nowhitespace', 'something with space', false],
            ['nowhitespace', "something\twith\tspace", false],
            ['nowhitespace', "something\nwith\nspace", false],
            ['nowhitespace', "somethingwithoutspace", true],

            ['length', 'a', false],
            ['length', 'thisisright', true],
            ['length', str_repeat('thisisright', 99), false],

            ['countrycode', 'xxx', false],
            ['countrycode', 'xx', false],
            ['countrycode', 'us', true],
            ['countrycode', 'py', true],

            ['array', 'something', false],
            ['array', [], true],
            ['array', new \ArrayObject, true],
            ['array', new \stdclass, false],

            ['object', 'somethign', false],
            ['object', [], false],
            ['object', new \ArrayObject, true],
            ['object', new \stdclass, true],

            ['charset', "\x99\xa1\xfa\xf0", false],
            ['charset', "hola", true],

            ['email', 'ce..s@php.net', false],
            ['email', 'ces.a.r@.php.net', false],
            ['email', 'crodas@php', false],
            ['email', 'crodas(at)php.net', false],
            ['email', 'crodas@php.net', true],

            ['date', '19-25-25', false],
            ['date', '1987-25-25', false],
            ['date', '1987-08-25', true],
            ['date', new \DateTime, true],

            ['integer', 'something', false],
            ['integer', 99.99, false],
            ['integer', 99, true],
            ['integer', 099, true],
            ['integer', 0x99, true],

            ['base64', base64_encode(uniqid(true)), true],
            ['base64', hex2bin(uniqid(true)), false],

            ['notempty', NULL, false],
            ['notempty', '', false],
            ['notempty', [], false],
            ['notempty', ['x'], true],
            ['notempty', 99, true],
            ['notempty', new \stdclass, true],

            ['xdigit', 'xff99', false],
            ['xdigit', 'ff99x', false],
            ['xdigit', 'ff99f', true],
            ['xdigit', '99', true],

            ['writable', new \SplFileInfo(__FILE__), true],
            ['writable', __FILE__, true],

            ['bool', false, true],
            ['bool', true, true],
            ['bool', '', false],
            ['bool', [], false],
            ['bool', 1, false],
            ['bool', 0, false],

            ['alnum', 'foo bar', false],
            ['alnum', 'foobar!', false],
            ['alnum', 'foobar', true],
            ['alnum', 'foobar', true],
            ['alnum', 'f00b4r', true],

            ['alpha', 'f00b4r', false],
            ['alpha', 'foobar', true],
        ];
    }

    /**
     *  @dataProvider provideTest
     *  @dependsOn testCompile
     */
    public function testAll($name, $input, $expect)
    {
        $this->assertEquals(f\validate('test_'. $name, $input), $expect);
    }

    /**
     *  @dataProvider provideTestInvalid
     *  @dependsOn testCompile
     *  @expectedException UnexpectedValueException
     *  @expectedExceptionMessage exception
     */
    public function testExceptionAll($name, $input, $expect)
    {
        f\validate('test_we_'. $name, $input);
    }
}
