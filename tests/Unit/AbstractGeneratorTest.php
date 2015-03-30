<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit;

class AbstractGeneratorTest extends TestCase
{

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataIndentationString()
    {
        return array([''], [' '], ["\t"], ["\n"], [' * '], [' 123 ']);
    }

    /**
     * @dataProvider getDataIndentationString
     */
    public function testSetIndentationString($is)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\AbstractGenerator');
        $return = $mock->setIndentationString($is);

        $this->assertSame($mock, $return);
        $this->assertSame($is, $mock->getIndentationString());
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataIndentationLevelPass()
    {
        return array(
            [0, 0],
            [1, 1],
            [1.1, 1],
            [2.5, 2],
            [2.7, 2],
            [INF, 0],
            ['0', 0],
            ['1', 1],
            ['1.1', 1],
            ['2.5', 2],
            ['somestring', 0],
            ['123string', 123]
        );
    }

    /**
     * @dataProvider getDataIndentationLevelPass
     */
    public function testSetIndentationLevel($il, $expected)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\AbstractGenerator');
        $return = $mock->setIndentationLevel($il);

        $this->assertSame($mock, $return);
        $this->assertSame($expected, $mock->getIndentationLevel());
    }

    public function getDataIndentationLevelFail()
    {
        return array([-5], ['-5']);
    }

    /**
     * @dataProvider getDataIndentationLevelFail
     * @expectedException \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testSetIndentationLevelException($il)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\AbstractGenerator');
        $mock->setIndentationLevel($il);
    }

    /**
     * @dataProvider getDataIndentationLevelPass
     */
    public function testAddIndentationLevel($il, $expected)
    {
        $presetIndentation = 5;
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\AbstractGenerator');
        $mock->setIndentationLevel($presetIndentation);
        $return = $mock->addIndentationLevel($il);

        $this->assertSame($mock, $return);
        $this->assertSame($presetIndentation + $expected, $mock->getIndentationLevel());
    }

    public function getDataIndentation()
    {
        return array(
            [' ', 1, ' '],
            [' * ', 1, ' * '],
            [' ', 4, '    '],
            [' * ', 4, ' *  *  *  * '],
        );
    }

    /**
     * @dataProvider getDataIndentation
     */
    public function testGetIndentation($is, $il, $expected)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\AbstractGenerator');
        $mock->setIndentationString($is);
        $mock->setIndentationLevel($il);

        $this->assertSame($expected, $this->callMethod($mock, 'getIndentation'));
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataLineFeed()
    {
        return array(
            [''],
            ['123'],
            ["\n"],
            ["\t"]
        );
    }

    /**
     * @dataProvider getDataLineFeed
     */
    public function testSetLineFeed($lineFeed)
    {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\AbstractGenerator');
        $return = $mock->setLineFeed($lineFeed);

        $this->assertSame($mock, $return);
        $this->assertSame($lineFeed, $mock->getLineFeed());
    }
}