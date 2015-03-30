<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\Expected\PHP\OOP\ModifiersStaticMock;
use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class ModifiersStaticTraitTest extends TestCase
{

    public function testSetStatic()
    {
        $mock = new ModifiersStaticMock();
        $mock->setStatic(false);
        $this->assertFalse($mock->isStatic());

        $mock->setStatic(true);
        $this->assertTrue($mock->isStatic());
    }
}