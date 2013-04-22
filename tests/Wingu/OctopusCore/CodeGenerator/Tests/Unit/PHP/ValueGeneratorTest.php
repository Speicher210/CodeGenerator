<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\ValueGenerator;
use Wingu\OctopusCore\CodeGenerator\Expression;

class ValueGeneratorTest extends TestCase {

    protected function getTestArray() {
        $array = array(
        		1,2,3.14,-4,5.01,
        		false, 'bool' => true,
        		'string', 'my "string"',
        		new Expression('SORT_ASC'),null,
        		'key' => 'value', '"key"' => 'value', "'key'" => 'value'
        );
        $array[] = new ValueGenerator($array);
        $array['sub']['sub2']['sub3'] = 3;
        $array['sub']['sub2']['sub4'] = 4;

        return $array;
    }

    protected function getTestArrayMultiLine() {
        $array = $this->getTestArray();
        return ['array' => $array, 'expected' => file_get_contents(dirname(__DIR__).'/Expected/PHP/ValueGeneratorArrayCodeResultMultiLine.txt')];
    }

    protected function getTestArraySingleLine() {
    	$array = $this->getTestArray();
    	return ['array' => $array, 'expected' => file_get_contents(dirname(__DIR__).'/Expected/PHP/ValueGeneratorArrayCodeResultSingleLine.txt')];
    }

    public function getDataValues() {
        return array(
            [null], ['string'], [false], [new \stdClass()],
            [1], [0], [1.1], [27041983], [array(1,2,'str')],
            [new Expression(null)], [new Expression('my expression')],
            [fopen(__FILE__, 'r')], [function(){}]
        );
    }

    /**
     * @dataProvider getDataValues
     */
    public function testSetGetValue($value) {
        $vg = new ValueGenerator($value);
        $this->assertSame($value, $vg->getValue());

        $vg = new ValueGenerator();
        $vg->setValue($value);
        $this->assertSame($value, $vg->getValue());
    }

    public function getDataTypes() {
        return array(
            [ValueGenerator::TYPE_ARRAY], [ValueGenerator::TYPE_AUTO], [ValueGenerator::TYPE_BOOLEAN], [ValueGenerator::TYPE_CONSTANT],
            [ValueGenerator::TYPE_DOUBLE], [ValueGenerator::TYPE_EXPRESSION], [ValueGenerator::TYPE_FLOAT], [ValueGenerator::TYPE_INTEGER],
            [ValueGenerator::TYPE_NULL], [ValueGenerator::TYPE_NUMBER], [ValueGenerator::TYPE_OBJECT], [ValueGenerator::TYPE_OTHER],
            [ValueGenerator::TYPE_STRING], ['non predefined type']
        );
    }

    /**
     * @dataProvider getDataTypes
     */
    public function testSetGetType($type) {
        $vg = new ValueGenerator(null, $type);
        $this->assertSame($type, $vg->getType());

        $vg = new ValueGenerator();
        $vg->setType($type);
        $this->assertSame($type, $vg->getType());
    }

    public function getDataOutputMode() {
        return array(
            [ValueGenerator::OUTPUT_MULTI_LINE, ValueGenerator::OUTPUT_MULTI_LINE],
            [ValueGenerator::OUTPUT_SINGLE_LINE, ValueGenerator::OUTPUT_SINGLE_LINE],
            ['dummy value', ValueGenerator::OUTPUT_SINGLE_LINE],
        );
    }

    /**
     * @dataProvider getDataOutputMode
     */
    public function testSetGetOutputMode($outputMode, $expected) {
        $vg = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\ValueGenerator', null);
        $vg->setOutputMode($outputMode);
        $this->assertSame($expected, $vg->getOutputMode());
    }

    public function getDataValidConstantValues() {
        return array(
            [null, ValueGenerator::TYPE_NULL], ['string', ValueGenerator::TYPE_STRING], [false, ValueGenerator::TYPE_BOOLEAN],
            [1234, ValueGenerator::TYPE_NUMBER], [-123, ValueGenerator::TYPE_INTEGER], [3.1415, ValueGenerator::TYPE_FLOAT],
            [88888888, ValueGenerator::TYPE_DOUBLE], ['SORT_ASC', ValueGenerator::TYPE_CONSTANT],
            ['some string', ValueGenerator::TYPE_AUTO], [new Expression(null), ValueGenerator::TYPE_EXPRESSION]
        );
    }

    /**
     * @dataProvider getDataValidConstantValues
     */
    public function testIsValidConstantType($value, $type) {
        $vg = new ValueGenerator($value, $type);
        $this->assertTrue($vg->isValidConstantType());
    }

    public function getDataInvalidConstantValues() {
    	return array(
			[[1,2], ValueGenerator::TYPE_ARRAY],
	        [new \stdClass(), ValueGenerator::TYPE_OBJECT], [fopen(__FILE__, 'r'), ValueGenerator::TYPE_OTHER],
			[[1,2], ValueGenerator::TYPE_AUTO]
    	);
    }

    /**
     * @dataProvider getDataInvalidConstantValues
     */
    public function testIsInvalidConstantType($value, $type) {
    	$vg = new ValueGenerator($value, $type);
    	$this->assertFalse($vg->isValidConstantType());
    }

    public function getDataDetermineType() {
        return array(
            [[1,2], ValueGenerator::TYPE_ARRAY], [false, ValueGenerator::TYPE_BOOLEAN], [null, ValueGenerator::TYPE_NULL],
            [-1, ValueGenerator::TYPE_INTEGER], [3.14, ValueGenerator::TYPE_NUMBER], [27041983, ValueGenerator::TYPE_INTEGER],
            [new Expression(null), ValueGenerator::TYPE_EXPRESSION], [new ValueGenerator(), ValueGenerator::TYPE_EXPRESSION],
            [new \stdClass(), ValueGenerator::TYPE_OBJECT], ['my string', ValueGenerator::TYPE_STRING],
            [fopen(__FILE__, 'r'), ValueGenerator::TYPE_OTHER],
        );
    }

    /**
     * @dataProvider getDataDetermineType
     */
    public function testDetermineType($value, $expectedType) {
        $vg = new ValueGenerator($value);
        $this->assertSame($expectedType, $this->callMethod($vg, 'determineType', [$value]));
    }

    public function getDataEscapeStringValue() {
        return array(
            [null, '', false], ['simple string', 'simple string', false],
            ['test\'\"', 'test\\\'\"', false], ['it\'s "quote"!', 'it\\\'s "quote"!', false],

            [null, "''", true], ['simple string', "'simple string'", true],
            ['test\'"', "'test\\'\"'", true], ['it\'s "quote"!', "'it\\'s \"quote\"!'", true],
        );
    }

    /**
     * @dataProvider getDataEscapeStringValue
     */
    public function testEscapeStringValue($value, $expected, $quote) {
        $vg = new ValueGenerator();
        $this->assertSame($expected, $this->callMethod($vg, 'escapeStringValue', [$value, $quote]));
    }

    public function getDataGenerate() {
        return array(
            [true, ValueGenerator::TYPE_BOOLEAN, 'true'], [false, ValueGenerator::TYPE_BOOLEAN, 'false'], [0, ValueGenerator::TYPE_BOOLEAN, 'false'],
            [null, ValueGenerator::TYPE_NULL, 'null'],
            [1, ValueGenerator::TYPE_NUMBER, '1'], [-5, ValueGenerator::TYPE_INTEGER, '-5'], [3.14, ValueGenerator::TYPE_FLOAT, '3.14'],
            [27041983, ValueGenerator::TYPE_DOUBLE, '27041983'], ['0.111', ValueGenerator::TYPE_NUMBER, '0.111'], ['SORT_ASC', ValueGenerator::TYPE_CONSTANT, 'SORT_ASC'],
            [new Expression('123'), ValueGenerator::TYPE_EXPRESSION, '123'], [new Expression('"some string"'), ValueGenerator::TYPE_EXPRESSION, '"some string"'],
            ['my string', ValueGenerator::TYPE_STRING, "'my string'"], ["multi\nline\nstring", ValueGenerator::TYPE_STRING, "'multi\nline\nstring'"],
            ['my "string"', ValueGenerator::TYPE_STRING, "'my \"string\"'"],
            [$this->getTestArrayMultiLine()['array'], ValueGenerator::TYPE_ARRAY, $this->getTestArrayMultiLine()['expected']],
        );
    }

    /**
     * @dataProvider getDataGenerate
     */
    public function testGenerate($value, $type, $expected) {
        $vg = new ValueGenerator($value, $type);
        $this->assertSame($expected, $vg->generate());
    }

    public function testGenerateSingleLine() {
        $vg = new ValueGenerator($this->getTestArraySingleLine()['array'], 'array', ValueGenerator::OUTPUT_SINGLE_LINE);
        $this->assertSame($this->getTestArraySingleLine()['expected'], $vg->generate());
    }

    public function getDataGenerateAutoType() {
    	return array(
			[true, 'true'], [false, 'false'], [null, 'null'],
			[1, '1'], [-5, '-5'], [3.14, '3.14'],
			[27041983, '27041983'], ['0.111', "'0.111'"], ['SORT_ASC', "'SORT_ASC'"],
			[new Expression('123'), '123'], [new Expression('"some string"'), '"some string"'],
			['my string', "'my string'"], ["multi\nline\nstring", "'multi\nline\nstring'"],
			['my "string"', "'my \"string\"'"],
            [$this->getTestArrayMultiLine()['array'], $this->getTestArrayMultiLine()['expected']],
    	);
    }

    /**
     * @dataProvider getDataGenerateAutoType
     */
    public function testGenerateAutoType($value, $expected) {
    	$vg = new ValueGenerator($value, ValueGenerator::TYPE_AUTO);
    	$this->assertSame($expected, $vg->generate());
    }

    public function getDataGenerateFail() {
        return array(
            [ValueGenerator::TYPE_OBJECT], [ValueGenerator::TYPE_OTHER], ['unknown type']
        );
    }

    /**
     * @dataProvider getDataGenerateFail
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\RuntimeException
     */
    public function testGenerateFail($type) {
        $vg = new ValueGenerator(null, $type);
        $vg->generate();
    }
}