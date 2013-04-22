<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\Tests\Unit\Expected\PHP\OOP\ModifiersVisibilityMock;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\Modifiers;

class ModifiersVisibilityTraitTest extends TestCase {

    public function getDataVisibility() {
    	return array(
			[Modifiers::VISIBILITY_PRIVATE],
			[Modifiers::VISIBILITY_PROTECTED],
			[Modifiers::VISIBILITY_PUBLIC],
    	);
    }

    /**
     * @dataProvider getDataVisibility
     */
    public function testSetGetVisibility($visibility) {
        $mock = new ModifiersVisibilityMock();
    	$mock->setVisibility($visibility);
    	$this->assertSame($visibility, $mock->getVisibility());
    }

    /**
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testSetVisibilityFail() {
        $mock = new ModifiersVisibilityMock();
    	$mock->setVisibility('dummy');
    }
}