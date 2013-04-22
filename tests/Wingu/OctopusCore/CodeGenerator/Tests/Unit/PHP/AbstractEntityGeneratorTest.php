<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\AbstractEntityGenerator;

class AbstractEntityGeneratorTest extends TestCase {

    public function getDataNameValid() {
    	return array(['name', 'name123']);
    }

    /**
     * @dataProvider getDataNameValid
     */
    public function testSetGetNameValid($name) {
    	$amg = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\PHP\AbstractEntityGenerator');
    	$amg->setName($name);

    	$this->assertSame($name, $amg->getName());
    }

    public function getDataNameInvalid() {
    	return array(['123name', 'name name', '__CLASS__']);
    }

    /**
     * @dataProvider getDataNameInvalid
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testSetGetNameInvalid($name) {
    	$amg = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\PHP\AbstractEntityGenerator');
    	$amg->setName($name);
    }

    public function getDataExtractShortNameFromFullyQualifiedName() {
        return array(
            ['name', 'name'], ['my\ns\name', 'name'], ['\myns\name', 'name']
        );
    }

    /**
     * @dataProvider getDataExtractShortNameFromFullyQualifiedName
     */
    public function testExtractShortNameFromFullyQualifiedName($name, $expected) {
        $this->assertSame($expected, AbstractEntityGenerator::extractShortNameFromFullyQualifiedName($name));
    }
}