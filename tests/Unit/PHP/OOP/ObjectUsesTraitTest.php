<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class ObjectUsesTraitTest extends TestCase
{

    public function testSetUses()
    {
        $mock = $this->getObjectForTrait('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ObjectUsesTrait');
        $uses = array();
        for ($i = 0; $i < 5; $i++) {
            $uses[] = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\UseTraitGenerator', [], [], '', false);
        }

        $mock->setTraitUses($uses);
        $this->assertCount(5, $mock->getTraitUses());
    }
}