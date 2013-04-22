<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class ParameterTraitTest extends TestCase {
    public function getDataParameters() {
    	$params = array();
    	for ($i = 0; $i < 5; $i++) {
    		$params['PGName' . $i] = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator', null, ['PGName' . $i]);
    	}

    	return array([array()], [$params]);
    }

    /**
     * @dataProvider getDataParameters
     */
    public function testSetGetParameters($parameters) {
    	$fg = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\FunctionGenerator', null, [], '', false);
    	$fg->setParameters($parameters);
    	$this->assertSame($parameters, $fg->getparameters());
    }

    /**
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testSetParametersDuplicate() {
    	$param = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator', null, ['duplicateparam']);

    	$fg = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\FunctionGenerator', null, [], '', false);
    	$fg->addParameter($param);
    	$fg->addParameter($param);
    }
}