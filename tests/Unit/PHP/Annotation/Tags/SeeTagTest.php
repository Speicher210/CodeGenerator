<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\Annotation\Tags;

use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\SeeTag;
use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class SeeTagTest extends TestCase
{

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataForAnnotationDefinition()
    {
        return array(
            [null, '@see'],
            ['See this link.', '@see See this link.'],
        );
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testSeeTagGeneration($description, $expected)
    {
        $seeTag = new SeeTag($description);
        $this->assertSame($expected, $seeTag->generate());
    }
}