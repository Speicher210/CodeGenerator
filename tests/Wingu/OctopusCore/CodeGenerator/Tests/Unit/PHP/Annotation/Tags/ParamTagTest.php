<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\Annotation\Tags;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\ParamTag;

class ParamTagTest extends TestCase {

    public function getDataForAnnotationDefinition() {
        return array(
            ['type1', 'name1', null, '@param type1 $name1'],
            ['type2', 'name2', '', '@param type2 $name2'], ['type2', 'name2', ' ', '@param type2 $name2'],
            ['type3', 'name3', ' description ', '@param type3 $name3  description'],
            ['type4', 'name4', 'description bar ', '@param type4 $name4 description bar'],
        );
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testParamTagGeneration($type, $name, $description, $expected) {
    	$returnTag = new ParamTag($type, $name, $description);

    	$this->assertSame($expected, $returnTag->generate());
    	$this->assertSame($expected, (string)$returnTag);
    }
}