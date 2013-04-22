<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class GlobalUseTraitTest extends TestCase {

    public function getDataGetSetuses() {
    	return array(
			[['myuse'], ['myuse' => null]],
			[['myuse', null], ['myuse' => null]],
			[['myuse', 'seconduse'], ['myuse' => null, 'seconduse' => null]],
			[[['use1', 'use_alias_1'], 'use2', ['use3', 'use_alias_3']], ['use1' => 'use_alias_1', 'use2'=>null, 'use3' => 'use_alias_3']]
    	);
    }

    /**
     * @dataProvider getDataGetSetuses
     */
    public function testGetSetUses($uses, $expected) {
    	$mock = $this->getObjectForTrait('Wingu\OctopusCore\CodeGenerator\PHP\GlobalUseTrait');

    	$mock->setUses($uses);
    	$this->assertSame($expected, $mock->getUses());
    }
}