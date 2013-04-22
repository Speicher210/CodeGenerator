<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\Expression;
use Wingu\OctopusCore\CodeGenerator\PHP\ValueGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator;

class ParameterGeneratorTest extends TestCase {

    public function getDataConstructor() {
        return array(
            ['name', [null, null], [null, null], false],
            ['my_name', [1, 1], ['\MyType', '\MyType'], true],
            ['my_name123', [[], []], [null, 'array'], false],
        );
    }

    /**
     * @dataProvider getDataConstructor
     */
    public function testConstructor($name, $defaultValue, $type, $passByReference) {
        $pg = new ParameterGenerator($name, $defaultValue[0], $type[0], $passByReference);

        $this->assertSame($name, $pg->getName());
        if ($pg->getDefaultValue() !== null) {
            $this->assertSame($defaultValue[1], $pg->getDefaultValue()->getValue());
        } else {
            $this->assertSame($defaultValue[1], null);
        }
        $this->assertSame($type[1], $pg->getType());
        $this->assertSame($passByReference, $pg->isPassByReference());
    }

    public function getDataNamesValid() {
        return array(['simple'], ['param1'], ['_myParam'], ['__CLASS__']);
    }

    /**
     * @dataProvider getDataNamesValid
     */
    public function testSetNameValid($name) {
        $pg = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator', null, [], '', false);
        $this->assertSame($pg, $pg->setName($name));
        $this->assertSame($name, $pg->getName());
    }

    public function getDataNamesInvalid() {
        return array(['1param'], ['param 1'], ['par1+par2'], ['par-am'], [''], [' '],
                [array('myParam')]);
    }

    /**
     * @dataProvider getDataNamesInvalid
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testSetNameInvalid($name) {
        $pg = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator', null, [], '', false);
        $pg->setName($name);
    }

    public function getDataTypesValid() {
        return array([null], ['array'], ['\n'], ['mytype'], ['sometype'], ['\my\ms\type'], ['\stdClass'],
                ['\some\ms\class13_456\sub'], ['\_some\_class\sub']);
    }

    /**
     * @dataProvider getDataTypesValid
     */
    public function testSetTypeValid($type) {
        $pg = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator', null, [], '', false);
        $this->assertSame($pg, $pg->setType($type));
        $this->assertSame($type, $pg->getType());
    }

    public function getDataTypesInvalid() {
        return array([''], ['  '], ['1abc'], ['a b c'], ['my-ns'], ['__CLASS__'], ['instanceof'], [123],
                ['\some\\\\ms\class\sub'], ['\1234some\class\sub'], ['\some\class\\'], ['\ns\123class'], ['some\class']);
    }

    /**
     * @dataProvider getDataTypesInvalid
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testSetTypeInvalid($type) {
        $pg = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator', null, [], '', false);
        $pg->setType($type);
    }

    public function testSetPassByReference() {
        $pg = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator', null, [], '', false);
        $this->assertSame($pg, $pg->setPassByReference(true));
        $this->assertTrue($pg->isPassByReference());

        $pg->setPassByReference(false);
        $this->assertFalse($pg->isPassByReference());
    }

    public function getDataDefaultValuesValid() {
        $expression1 = $this->getMock('Wingu\OctopusCore\CodeGenerator\Expression', null, [null], 'expression1');
        $expression2 = $this->getMock('Wingu\OctopusCore\CodeGenerator\Expression', null, ['__DIR__'], 'expression2');
        return array([null, null], ['', ''], ['default', 'default'], [false, false], [1, 1], [3.14, 3.14],
                [SORT_ASC, SORT_ASC], [array(1, 2, 3), array(1, 2, 3)], [$expression1, $expression1], [$expression2, $expression2],
                [new ValueGenerator('val'), 'val']);
    }

    /**
     * @dataProvider getDataDefaultValuesValid
     */
    public function testSetDefaultValueValid($value, $expected) {
        $pg = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator', null, [], '', false);
        $pg->setDefaultValue($value);
        $backValue = $pg->getDefaultValue();
        $this->assertInstanceOf('Wingu\OctopusCore\CodeGenerator\PHP\ValueGenerator', $backValue);
        $this->assertSame($expected, $backValue->getValue());
    }

    public function getDataDefaultValuesInvalid() {
        return array([fopen(__FILE__, 'r')], [new \stdClass()], [new ValueGenerator('some_val', ValueGenerator::TYPE_OBJECT)]);
    }

    /**
     * @dataProvider getDataDefaultValuesInvalid
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testSetGetDefaultValueInvalid($value) {
        $pg = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator', null, [], '', false);
        $pg->setDefaultValue($value);
    }

    public function getDataDefaultValueConstantNameInvalid() {
        return array(
            [null], [''], [' '], ['test '], ["test\ntest"], [5], [STDIN]
        );
    }

    /**
     * @dataProvider getDataDefaultValueConstantNameInvalid
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testSetDefaultValueConstantName($name) {
        $pg = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator', null, [], '', false);
        $pg->setDefaultValueConstantName($name);
    }

    public function getDataDetectParameterType() {
        return array(
            [null, null], [-5, null], [3.14, null], ['string', null], [array(), 'array'], [[], 'array'], [array(1,2,3), 'array']
        );
    }

    /**
     * @dataProvider getDataDetectParameterType
     */
    public function testDetectParameterType($value, $expectedType) {
        $pg = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator', null, [], '', false);
        $pg->setDefaultValue($value);
        $this->callMethod($pg, 'detectParameterType');

        $this->assertSame($expectedType, $pg->getType());
    }

    public function getDataGenerate() {
        return array(
            ['param', null, null, false, '$param'],
            ['param', 1, null, false, '$param = 1'],
            ['param', null, null, true, '&$param'],
            ['param', null, '\stdClass', false, '\stdClass $param'],
            ['param', [1,2,3], 'array', false, 'array $param = array(1, 2, 3)'],
            ['sort', new Expression('SORT_ASC'), null, false, '$sort = SORT_ASC']
        );
    }

    /**
     * @dataProvider getDataGenerate
     */
    public function testGenerate($name, $defaultValue, $type, $passByReference, $expectedResult) {
        $pg = new ParameterGenerator($name, $defaultValue, $type, $passByReference);

        $this->assertSame($expectedResult, $pg->generate());
    }
}