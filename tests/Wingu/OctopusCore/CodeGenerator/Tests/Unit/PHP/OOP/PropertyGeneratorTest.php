<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\PHP\OOP\Modifiers;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\ValueGenerator;
use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class PropertyGeneratorTest extends TestCase
{

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataNamesValid()
    {
        return array(['myname'], ['name123'], ['_name_'], ['n1n'], ['__CLASS__'], ['xor']);
    }

    /**
     * @dataProvider getDataNamesValid
     */
    public function testSetGetName($name)
    {
        $pg = $this->getMock(
            'Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator',
            null,
            [],
            'PGNameValid',
            false
        );
        $pg->setName($name);

        $this->assertSame($name, $pg->getName());
    }

    public function getDataNamesInvalid()
    {
        return array(
            ['  '],
            ['my name'],
            ['123name'],
            ['name-name'],
            ['n,n'],
            [null],
            [new \stdClass()]
        );
    }

    /**
     * @dataProvider getDataNamesInvalid
     * @expectedException \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testSetNameInvalid($name)
    {
        $pg = $this->getMock(
            'Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator',
            null,
            [],
            'PGNameInvalid',
            false
        );
        $pg->setName($name);
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataValuesValid()
    {
        return array(
            [null, null],
            [false, false],
            [true, true],
            [-1, -1],
            [3.14, 3.14],
            [SORT_ASC, SORT_ASC],
            ['my value', 'my value'],
            [array(1, 2, 3), array(1, 2, 3)],
            [new ValueGenerator('value generator'), 'value generator']
        );
    }

    /**
     * @dataProvider getDataValuesValid
     */
    public function testSetGetValue($value, $expected)
    {
        $pg = $this->getMock(
            'Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator',
            null,
            [],
            'PGValueValid',
            false
        );
        $pg->setDefaultValue($value);
        $this->assertSame($expected, $pg->getDefaultValue()->getValue());
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataValuesInvalid()
    {
        return array([new \stdClass()], [new ValueGenerator(new \stdClass())], [fopen(__FILE__, 'r')]);
    }

    /**
     * @dataProvider getDataValuesInvalid
     * @expectedException \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testSetValueInvalid($value)
    {
        $pg = $this->getMock(
            'Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator',
            null,
            [],
            'PGValueInvalid',
            false
        );
        $pg->setDefaultValue($value);
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataGenerate()
    {
        $doc = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator', ['generate', '__toString']);
        $doc->expects($this->at(0))->method('generate')->will($this->returnValue("/**\n *\n */"));
        $doc->expects($this->at(1))->method('generate')->will(
            $this->returnValue("/**\n * Short desc.\n * \n * Long description.\n * \n * @var string Some param6.\n */")
        );

        return array(
            ["public \$prop1 = 'string';", 'prop1', 'string'],
            [
                "        public \$prop2 = 'string';",
                'prop2',
                'string',
                null,
                null,
                2
            ],
            ["public \$prop3 = 'it\\'s great';", 'prop3', "it's great"],
            ["public \$prop4;", 'prop4', null],
            [
                "/**\n *\n */\nprotected static \$prop5 = 'my string';",
                'prop5',
                'my string',
                Modifiers::MODIFIER_PROTECTED | Modifiers::MODIFIER_STATIC,
                $doc
            ],
            [
                "/**\n * Short desc.\n * \n * Long description.\n * \n * @var string Some param6.\n */\nprivate \$param6 = 'my string';",
                'param6',
                'my string',
                Modifiers::MODIFIER_PRIVATE,
                $doc
            ]
        );
    }

    /**
     * @dataProvider getDataGenerate
     */
    public function testGenerate($expected, $name, $defaultValue, $modifiers = null, $doc = null, $indent = null)
    {
        if ($modifiers === null) {
            $pg = new PropertyGenerator($name, $defaultValue);
        } else {
            $pg = new PropertyGenerator($name, $defaultValue, $modifiers);
        }

        if ($doc !== null) {
            $pg->setDocumentation($doc);
        }

        if ($indent !== null) {
            $pg->setIndentationLevel($indent);
        }

        $this->assertSame($expected, $pg->generate());
    }
}