<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\Annotation\Tags;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\PropertyTag;

class PropertyTagTest extends TestCase {

    public function getDataForAnnotationDefinition() {
        return array(
            ['type1', 'prop1', null, PropertyTag::ACCESS_FULL, '@property type1 $prop1'],
            ['type2', 'prop2', 'Some property.', PropertyTag::ACCESS_FULL, '@property type2 $prop2 Some property.'],
            ['string', 'prop3', 'Read only property.', PropertyTag::ACCESS_READ, '@property-read string $prop3 Read only property.'],
            ['string', 'prop4', 'Write only property.', PropertyTag::ACCESS_WRITE, '@property-write string $prop4 Write only property.'],
        );
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testPropertyTagGeneration($type, $name, $description, $access, $expected) {
    	$propertyTag = new PropertyTag($type, $name, $description, $access);
    	$this->assertSame($expected, $propertyTag->generate());
    }
}