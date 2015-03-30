<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\Expected\PHP\OOP\ModifiersFinalMock;
use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class ModifiersFinalTraitTest extends TestCase
{

    public function testSetGetFinal()
    {
        $mock = new ModifiersFinalMock();

        $mock->setFinal(true);
        $this->assertTrue($mock->isFinal());

        $mock->setFinal(false);
        $this->assertFalse($mock->isFinal());
    }
}