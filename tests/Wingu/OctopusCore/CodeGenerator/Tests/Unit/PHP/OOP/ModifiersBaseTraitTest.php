<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\PHP\OOP\Modifiers;
use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class ModifiersBaseTraitTest extends TestCase
{

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataSetModifiers()
    {
        return array(
            [0, 0],
            [Modifiers::MODIFIER_PRIVATE, Modifiers::MODIFIER_PRIVATE],
            [
                Modifiers::MODIFIER_PROTECTED | Modifiers::MODIFIER_STATIC,
                Modifiers::MODIFIER_PROTECTED | Modifiers::MODIFIER_STATIC
            ],
            [
                [Modifiers::MODIFIER_PROTECTED, Modifiers::MODIFIER_STATIC],
                Modifiers::MODIFIER_PROTECTED | Modifiers::MODIFIER_STATIC
            ]
        );
    }

    /**
     * @dataProvider getDataSetModifiers
     */
    public function testSetModifiers($modifiers, $expected)
    {
        $mock = $this->getObjectForTrait('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ModifiersBaseTrait');
        $this->callMethod($mock, 'setModifiers', [$modifiers]);
        $this->assertSame($expected, $this->getProperty($mock, 'modifiers'));
    }

    public function getDataAddModifiers()
    {
        return array(
            [0, 0],
            [Modifiers::MODIFIER_PRIVATE, Modifiers::MODIFIER_PRIVATE],
            [
                Modifiers::MODIFIER_PROTECTED | Modifiers::MODIFIER_STATIC,
                Modifiers::MODIFIER_PROTECTED | Modifiers::MODIFIER_STATIC
            ]
        );
    }

    /**
     * @dataProvider getDataAddModifiers
     */
    public function testAddModifiers($modifier, $expected)
    {
        $mock = $this->getObjectForTrait('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ModifiersBaseTrait');

        $this->callMethod($mock, 'setModifiers', [0]);
        $this->callMethod($mock, 'addModifier', [$modifier]);
        $this->assertSame($expected, $this->getProperty($mock, 'modifiers'));
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataRemoveModifiers()
    {
        return array(
            [0, 0, 0],
            [
                Modifiers::MODIFIER_PRIVATE | Modifiers::MODIFIER_STATIC,
                Modifiers::MODIFIER_STATIC,
                Modifiers::MODIFIER_PRIVATE
            ],
        );
    }

    /**
     * @dataProvider getDataRemoveModifiers
     */
    public function testRemoveModifiers($initialModifiers, $remove, $expected)
    {
        $mock = $this->getObjectForTrait('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ModifiersBaseTrait');

        $this->callMethod($mock, 'setModifiers', [$initialModifiers]);
        $this->callMethod($mock, 'removeModifier', [$remove]);
        $this->assertSame($expected, $this->getProperty($mock, 'modifiers'));
    }
}