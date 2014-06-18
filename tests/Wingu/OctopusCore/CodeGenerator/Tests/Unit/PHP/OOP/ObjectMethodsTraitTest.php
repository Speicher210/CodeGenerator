<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class ObjectMethodsTraitTest extends TestCase
{

    protected function getMethodMock($identifier)
    {
        $method = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator', ['getName'], [], '', false);
        $method->expects($this->any())->method('getName')->will($this->returnValue('method' . $identifier));
        return $method;
    }

    public function testSetMethods()
    {
        $mock = $this->getObjectForTrait('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ObjectMethodsTrait');
        $methods = array();
        for ($i = 0; $i < 5; $i++) {
            $methods[] = $this->getMethodMock($i);
        }

        $mock->setMethods($methods);
        $this->assertCount(5, $mock->getMethods());
        for ($i = 0; $i < 5; $i++) {
            $this->assertTrue($mock->hasMethod('method' . $i));
        }
    }

    /**
     * @expectedException \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testAddMethodTwice()
    {
        $mock = $this->getObjectForTrait('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ObjectMethodsTrait');
        $mock->addMethod($this->getMethodMock('same'));
        $mock->addMethod($this->getMethodMock('same'));
    }

    public function testGetExistingMethod()
    {
        $mock = $this->getObjectForTrait('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ObjectMethodsTrait');
        $actual = $this->getMethodMock('ExistingMethod');

        $mock->addMethod($actual);
        $expected = $mock->getMethod('methodExistingMethod');
        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testGetNonExistingMethod()
    {
        $mock = $this->getObjectForTrait('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ObjectMethodsTrait');
        $mock->addMethod($this->getMethodMock('NonExistingMethod'));
        $mock->getMethod('methodExistingMethod');
    }
}