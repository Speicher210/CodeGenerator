<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class ObjectPropertiesTraitTest extends TestCase
{

    protected function getPropertyMock($identifier)
    {
        $property = $this->getMock(
            'Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator',
            ['getName'],
            [],
            '',
            false
        );
        $property->expects($this->any())->method('getName')->will($this->returnValue('property' . $identifier));
        return $property;
    }

    public function testSetProperties()
    {
        $mock = $this->getObjectForTrait('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ObjectPropertiesTrait');
        $properties = array();
        for ($i = 0; $i < 5; $i++) {
            $properties[] = $this->getPropertyMock($i);
        }

        $mock->setProperties($properties);
        $this->assertCount(5, $mock->getProperties());
        for ($i = 0; $i < 5; $i++) {
            $this->assertTrue($mock->hasProperty('property' . $i));
        }
    }

    /**
     * @expectedException \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testAddPropertyTwice()
    {
        $mock = $this->getObjectForTrait('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ObjectPropertiesTrait');
        $mock->addProperty($this->getPropertyMock('same'));
        $mock->addProperty($this->getPropertyMock('same'));
    }
}