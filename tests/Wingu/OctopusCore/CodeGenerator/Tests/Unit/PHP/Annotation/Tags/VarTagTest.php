<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\Annotation\Tags;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\VarTag;

class VarTagTest extends TestCase {

    public function getDataForAnnotationDefinition() {
        return array(
            ['string', '@var string'],
            ['', '@var'], [' ', '@var'],
            [' array ', '@var array'],
            ['\DateTime ', '@var \DateTime'],
        );
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testVarTagGeneration($name, $expected) {
    	$varTag = new VarTag($name, $expected);

    	$this->assertSame($expected, $varTag->generate());
    	$this->assertSame($expected, (string)$varTag);
    }
}