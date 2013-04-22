<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\ValueGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassConstantGenerator;

class ClassConstantGeneratorTest extends TestCase {

    public function getDataNamesValid() {
        return array(
            ['myname'], ['name123'], ['_name_'], ['n1n']
        );
    }

    /**
     * @dataProvider getDataNamesValid
     */
    public function testSetGetName($name) {
        $classConstant = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassConstantGenerator', null, [], 'CCGNameValid', false);
        $classConstant->setName($name);

        $this->assertSame($name, $classConstant->getName());
    }

    public function getDataNamesInvalid() {
    	return array(
			['  '], ['my name'], ['123name'], ['name-name'], ['n,n'],
	        ['__CLASS__'], ['xor'], [null], [new \stdClass()],
    	);
    }

    /**
     * @dataProvider getDataNamesInvalid
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testSetNameInvalid($name) {
    	$classConstant = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassConstantGenerator', null, [], 'CCGNameInvalid', false);
    	$classConstant->setName($name);
    }

    public function getDataValuesValid() {
        return array(
            [null],[false],[true],[-1],[3.14],[SORT_ASC],['my value'],
            [new ValueGenerator('value generator')]
        );
    }

    /**
     * @dataProvider getDataValuesValid
     */
    public function testSetGetValue($value) {
        $classConstant = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassConstantGenerator', null, [], 'CCGValueValid', false);
        $classConstant->setValue($value);
    }

    public function getDataValuesInvalid() {
    	return array(
			[array(1,2,'3')],[new \stdClass()],[new ValueGenerator(array())],[fopen(__FILE__, 'r')]
    	);
    }

    /**
     * @dataProvider getDataValuesInvalid
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testSetValueInvalid($value) {
    	$classConstant = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassConstantGenerator', null, [], 'CCGValueInvalid', false);
    	$classConstant->setValue($value);
    }

    public function getDataGenerate() {
        $doc = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator', ['generate', '__toString']);
        $doc->expects($this->at(0))
            ->method('generate')
            ->will($this->returnValue("/**\n *\n */"));
        $doc->expects($this->at(1))
            ->method('generate')
            ->will($this->returnValue("/**\n * Short desc.\n * \n * Long description.\n * \n * @param string \$param1 Some param1.\n */"));

        return array(
            ["const const1 = 'string';", 'const1', 'string'],
            ["            const const2 = 'string';", 'const2', 'string', null, 3],
            ["const const3 = 'it\\'s great';", 'const3', "it's great"],
            ["const const4 = null;", 'const4', null],
            ["/**\n *\n */\nconst const5 = 'my string';", 'const5', 'my string', $doc],
            ["/**\n * Short desc.\n * \n * Long description.\n * \n * @param string \$param1 Some param1.\n */\nconst const6 = 'my string';", 'const6', 'my string', $doc],
        );
    }

    /**
     * @dataProvider getDataGenerate
     */
    public function testGenerate($expected, $name, $value, $doc = null, $indent = null) {
        $ccg = new ClassConstantGenerator($name, $value);
        if ($doc !== null) {
            $ccg->setDocumentation($doc);
        }

        if ($indent !== null) {
            $ccg->setIndentationLevel($indent);
        }

        $this->assertSame($expected, $ccg->generate());
    }
}