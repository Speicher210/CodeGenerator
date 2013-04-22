<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\Annotation\Tags;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\SeeTag;

class SeeTagTest extends TestCase {

    public function getDataForAnnotationDefinition() {
        return array(
            [null, '@see'],
            ['See this link.', '@see See this link.'],
        );
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testSeeTagGeneration($description, $expected) {
    	$seeTag = new SeeTag($description);
    	$this->assertSame($expected, $seeTag->generate());
    }
}