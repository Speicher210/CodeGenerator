<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class PHPGeneratorTest extends TestCase
{

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataNamespaceValid()
    {
        return array(['\\'], ['test'], ['test\test'], ['\test'], ['\test\test_test'], ['ns1\ns2ns\ns3\ns4']);
    }

    /**
     * @dataProvider getDataNamespaceValid
     */
    public function testIsNamespaceValidTrue($ns)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\PHP\PHPGenerator');
        $this->assertTrue($this->callMethod($mock, 'isNamespaceValid', [$ns]));
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataNamespaceInvalid()
    {
        return array(
            [' '],
            ['1'],
            ['my ns'],
            ['test\\\Test'],
            ['test\\\\\\\\\\Test'],
            ['good\ test'],
            ['good\.test'],
            ['good\-test'],
            ['4bad\123test'],
            ['good\123test'],
            ['.ns\good'],
            ['namespace'],
            ['class'],
            ['__FILE__'],
            ['myNS\\__CLASS__']
        );
    }

    /**
     * @dataProvider getDataNamespaceInvalid
     */
    public function testIsNamespaceValidFalse($ns)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\PHP\PHPGenerator');
        $this->assertFalse($this->callMethod($mock, 'isNamespaceValid', [$ns]));
    }

    /**
     * @dataProvider getDataNamespaceValid
     */
    public function testIsObjectNameTrue($name)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\PHP\PHPGenerator');
        $this->assertTrue($this->callMethod($mock, 'isObjectNameValid', [$name]));
    }

    /**
     * @dataProvider getDataNamespaceInvalid
     */
    public function testIsObjectNameFalse($name)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\PHP\PHPGenerator');
        $this->assertFalse($this->callMethod($mock, 'isObjectNameValid', [$name]));
    }

    public function getDataEntityNameValid()
    {
        return array(['test'], ['test123'], ['test_test'], ['test_123']);
    }

    /**
     * @dataProvider getDataEntityNameValid
     */
    public function testIsEntityNameValidTrue($name)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\PHP\PHPGenerator');
        $this->assertTrue($this->callMethod($mock, 'isEntityNameValid', [$name]));
    }

    public function getDataEntityNameInvalid()
    {
        return array(['1'], ['my object'], ['test\\\Test'], ['.test'], ['test.'], ['123Test'], ['class'], ['__DIR__']);
    }

    /**
     * @dataProvider getDataEntityNameInvalid
     */
    public function testIsEntityNameValidFalse($name)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\PHP\PHPGenerator');
        $this->assertFalse($this->callMethod($mock, 'isEntityNameValid', [$name]));
    }
}