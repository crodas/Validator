<?php

use ActiveMongo2\Tests\Document\PostNoTitleDocument;

use test\FormValidatorEncoding as f;

class CharsetTest extends \phpunit_framework_testcase
{
    public function testGenerate()
    {
        $validator = new crodas\Validator\Builder;
        $validator->setNamespace("test\\formValidatorEncoding");
        
        $rules = $validator->createTest('ascii')
            ->addRule('charset', ['ascii'], 'Invalid charset');

        $rules = $validator->createTest('utf-8')
            ->addRule('charset', ['utf-8'], 'Invalid charset');

        $rules = $validator->createTest('utf8_or_8859')
            ->addRule('charset', ['utf-8', 'iso-8859-1'], 'Invalid charset');

        $validator->writeTo(__DIR__ . '/tmp/encoding.php');
        $this->assertFalse(is_callable('test\\FormValidatorEncoding\\validate'));
        require __DIR__ . '/tmp/encoding.php';
        $this->assertTrue(is_callable('test\\FormValidatorEncoding\\validate'));
    }

    /** 
     *  @dependsOn testGenerate
     *  @dataProvider providerValid
     */
    public function testValid($charset, $text)
    {
        $this->AssertTrue(f\validate(strtolower($charset), $text));
    }

    /** 
     *  @dependsOn testGenerate
     *  @dataProvider providerInvalid
     *  @expectedException UnexpectedValueException
     *  @expectedExceptionMessage Invalid
     */
    public function testInvalid($charset, $text)
    {
        f\validate(strtolower($charset), $text);
    }

    public static function providerValid()
    {
        return [
            ['ASCII', 'hola que tal?'],
            ['ASCII', 'un texto sin nada raro'],
            ['UTF8_OR_8859', '日本国'],
            ['UTF8_OR_8859', mb_convert_encoding('Holá!', 'ISO-8859-1')],
            ['UTF-8', mb_convert_encoding('hola qué', 'UTF-8')],
            ['UTF-8', mb_convert_encoding('日本国', 'UTF-8')],
        ];
    }

    public static function providerInvalid()
    {
        return [
            ['ASCII', []],
            ['ASCII', new \stdclass],
            ['ASCII', '¿hola qué tal?'],
            ['ASCII', '日本国'],
            ['UTF-8', mb_convert_encoding('hola qué', 'UTF-16')],
            ['UTF-8', mb_convert_encoding('日本国', 'UTF-16')],
        ];
    }
}
