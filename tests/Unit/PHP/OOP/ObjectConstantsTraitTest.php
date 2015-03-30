<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class ObjectConstantsTraitTest extends TestCase
{

    protected function getConstantMock($identifier)
    {
        $const = $this->getMock(
            'Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassConstantGenerator',
            ['getName'],
            [],
            '',
            false
        );
        $const->expects($this->any())->method('getName')->will($this->returnValue('const' . $identifier));
        return $const;
    }

    public function testSetConstants()
    {
        $mock = $this->getObjectForTrait('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ObjectConstantsTrait');
        $constants = array();
        for ($i = 0; $i < 5; $i++) {
            $constants[] = $this->getConstantMock($i);
        }

        $mock->setConstants($constants);
        $this->assertCount(5, $mock->getConstants());
        for ($i = 0; $i < 5; $i++) {
            $this->assertTrue($mock->hasConstant('const' . $i));
        }
    }

    /**
     * @expectedException \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testAddConstantTwice()
    {
        $mock = $this->getObjectForTrait('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ObjectConstantsTrait');
        $mock->addConstant($this->getConstantMock('same'));
        $mock->addConstant($this->getConstantMock('same'));
    }
}