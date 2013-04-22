<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\Annotation\Tags;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\MethodTag;

class MethodTagTest extends TestCase {

    public function getDataForAnnotationDefinition() {
        return array(
            ['method1', null, null, '@method void method1'],
            ['method2', 'integer', null, '@method integer method2'],
            ['method3', 'string', 'Some description.', '@method string method3 Some description.']
        );
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testPropertyTagGeneration($name, $return, $description, $expected) {
    	$propertyTag = new MethodTag($name, $return, $description);
    	$this->assertSame($expected, $propertyTag->generate());
    }

    public function getDataForMethodReturn() {
        return array(
            [null, 'void'], ['', 'void'], [' ', 'void'], ["\n", 'void'],
            ['integer', 'integer'], ['string', 'string'], ['DateTime', 'DateTime'],
            ['Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\MethodTag', 'Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\MethodTag']
        );
    }

    /**
     * @dataProvider getDataForMethodReturn
     */
    public function testMethodReturn($return, $expected) {
        $propertyTag = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\MethodTag', null, [], '', false);
        $propertyTag->setMethodReturn($return);
        $this->assertSame($expected, $propertyTag->getMethodReturn());
    }
}