<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\Annotation\Tags;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\ReturnTag;

class ReturnTagTest extends TestCase {

    public function getDataForAnnotationDefinition() {
        return array(
            ['type1', null, 'type1', '@return type1'],
            ['type2', '', 'type2', '@return type2'], ['type2', ' ', 'type2', '@return type2'],
            ['type3', ' description ', 'type3  description', '@return type3  description'],
            ['type4', 'description bar ', 'type4 description bar', '@return type4 description bar'],
        );
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testReturnTagDescription($type, $description) {
    	$returnTag = new ReturnTag($type, $description);
    	$this->assertSame($description, $returnTag->getDescription());
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testReturnTagGeneratedDescriptionPart($type, $description, $expectedDescriptionPartGeneration) {
    	$returnTag = new ReturnTag($type, $description);
    	$this->assertSame($expectedDescriptionPartGeneration, $this->callMethod($returnTag, 'generateDescriptionPart'));
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testReturnTagGeneration($type, $description, $expectedDescriptionPartGeneration, $expectedGeneration) {
    	$returnTag = new ReturnTag($type, $description);

    	$this->assertSame($expectedGeneration, $returnTag->generate());
    	$this->assertSame($expectedGeneration, (string)$returnTag);
    }
}