<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\Annotation\Tags;

use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\ThrowsTag;
use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class ThrowsTagTest extends TestCase
{

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataForAnnotationDefinition()
    {
        return array(
            ['Exception', null, '@throws Exception'],
            ['Exception', '', '@throws Exception'],
            ['Exception', ' ', '@throws Exception'],
            ['\MyNS\Exception', 'If there is a problem.', '@throws \MyNS\Exception If there is a problem.']
        );
    }

    /**
     * @dataProvider getDataForAnnotationDefinition
     */
    public function testParamTagGeneration($exception, $description, $expected)
    {
        $returnTag = new ThrowsTag($exception, $description);

        $this->assertSame($expected, $returnTag->generate());
        $this->assertSame($expected, (string)$returnTag);
    }
}