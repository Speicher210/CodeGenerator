<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\Annotation\Tags;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\BaseTag;

class BaseTagTest extends TestCase {

    public function getDataForAnnotationDefinition() {
        return array(
            ['tag0', null, '@tag0'],
            ['tag1', '', '@tag1'], ['tag1', ' ', '@tag1'],
            ['tag2', ' description ', '@tag2  description'],
            ['tag3', 'description bar ', '@tag3 description bar'],
        );
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testBaseTagName($name) {
        $baseTag = new BaseTag($name);
        $this->assertSame($name, $baseTag->getTagName());
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testBaseTagDescription($name, $description) {
    	$baseTag = new BaseTag($name, $description);
    	$this->assertSame($description, $baseTag->getDescription());
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testBaseTagGeneratedDescriptionPart($name, $description) {
    	$baseTag = new BaseTag($name, $description);
    	$this->assertSame($description, $this->callMethod($baseTag, 'generateDescriptionPart'));
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testBaseTagGeneration($name, $description, $expectedGeneration) {
    	$baseTag = new BaseTag($name, $description);

    	$this->assertSame($expectedGeneration, $baseTag->generate());
    	$this->assertSame($expectedGeneration, (string)$baseTag);
    }

    public function testFromReflection() {
        $rtName = 'myTag';
        $rtDescription = 'My tag reflection';

    	$reflectionMock = $this->getMock('Wingu\OctopusCore\Reflection\Annotation\Tags\BaseTag', ['getTagName','getDescription'], [], '', false);
    	$reflectionMock->expects($this->any())
        	->method('getTagName')
        	->will($this->returnValue($rtName));
    	$reflectionMock->expects($this->any())
        	->method('getDescription')
        	->will($this->returnValue($rtDescription));

    	$tg = BaseTag::fromReflection($reflectionMock);
    	$this->assertSame($rtName, $tg->getTagName());
    	$this->assertSame($rtDescription, $tg->getDescription());
    }
}