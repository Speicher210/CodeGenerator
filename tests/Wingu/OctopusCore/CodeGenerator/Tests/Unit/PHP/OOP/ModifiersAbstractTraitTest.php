<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\Tests\Unit\Expected\PHP\OOP\ModifiersAbstractMock;

class ModifiersAbstractTraitTest extends TestCase {

    public function testSetGetFinal() {
        $mock = new ModifiersAbstractMock();

        $mock->setAbstract(true);
        $this->assertTrue($mock->isAbstract());

        $mock->setAbstract(false);
        $this->assertFalse($mock->isAbstract());
    }
}