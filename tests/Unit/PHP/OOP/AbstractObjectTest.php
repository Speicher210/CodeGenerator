<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class AbstractObjectTest extends TestCase
{

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataNamespacePass()
    {
        return array(
            ['', null],
            ['\\', null],
            ['test', 'test'],
            ['test\test', 'test\test'],
            ['\test', 'test'],
            ['\test\test_test', 'test\test_test'],
            ['ns1\ns2ns\ns3\ns4', 'ns1\ns2ns\ns3\ns4'],
        );
    }

    /**
     * @dataProvider getDataNamespacePass
     */
    public function testSetNamespacePass($ns, $expected)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\PHP\OOP\AbstractObject');
        $mock->setNamespace($ns);

        $this->assertSame($expected, $mock->getNamespace());
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataNamespaceFail()
    {
        return array(
            [' ', null],
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
        );
    }

    /**
     * @dataProvider getDataNamespaceFail
     * @expectedException \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testSetNamespaceFail($ns)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\PHP\OOP\AbstractObject');
        $mock->setNamespace($ns);
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataNamePass()
    {
        return array(
            ['test'],
            ['test123'],
            ['test_test'],
            ['test_123']
        );
    }

    /**
     * @dataProvider getDataNamePass
     */
    public function testSetNamePass($name)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\PHP\OOP\AbstractObject');
        $mock->setName($name);

        $this->assertSame($name, $mock->getName());
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataNameFail()
    {
        return array(
            ['1'],
            ['my object'],
            ['test\\\Test'],
            ['.test'],
            ['test.'],
            ['123Test'],
            ['class'],
            ['__DIR__'],
        );
    }

    /**
     * @dataProvider getDataNameFail
     * @expectedException \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testSetNameFail($ns)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\PHP\OOP\AbstractObject');
        $mock->setName($ns);
    }

    public function getDataQualifiedName()
    {
        return array(
            [null, 'Test', 'Test', '\Test'],
            [null, 'Test123', 'Test123', '\Test123'],
            ['MyNS', 'Test', 'MyNS\Test', '\MyNS\Test'],
            ['MyNS\SubNS', 'Test123', 'MyNS\SubNS\Test123', '\MyNS\SubNS\Test123'],
            ['\MyNS', 'Test', 'MyNS\Test', '\MyNS\Test'],
            ['\MyNS\SubNS', 'Test123', 'MyNS\SubNS\Test123', '\MyNS\SubNS\Test123'],
        );
    }

    /**
     * @dataProvider getDataQualifiedName
     */
    public function testGetQualifiedName($ns, $name, $expected)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\PHP\OOP\AbstractObject');
        $mock->setNamespace($ns);
        $mock->setName($name);

        $this->assertSame($expected, $mock->getQualifiedName());
    }

    /**
     * @dataProvider getDataQualifiedName
     */
    public function testGetFullyQualifiedName($ns, $name, $qualifiedName, $fullyQualifiedName)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\PHP\OOP\AbstractObject');
        $mock->setNamespace($ns);
        $mock->setName($name);

        $this->assertSame($fullyQualifiedName, $mock->getFullyQualifiedName());
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataUses()
    {
        return array(
            [['use1'], ['use1' => null]],
            [['use1', ['use2', 'alias2']], ['use1' => null, 'use2' => 'alias2']],
        );
    }

    /**
     * @dataProvider getDataUses
     */
    public function testSetGetUses($uses, $expected)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\PHP\OOP\AbstractObject');
        $mock->setUses($uses);

        $this->assertSame($expected, $mock->getUses());
    }

}