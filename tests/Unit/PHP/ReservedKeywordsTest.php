<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP;

use Wingu\OctopusCore\CodeGenerator\PHP\ReservedKeywords;
use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class ReservedKeywordsTest extends TestCase
{

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataKeywords()
    {
        return array(['__CLASS__'], ['instanceof'], ['__file__']);
    }

    /**
     * @dataProvider getDataKeywords
     */
    public function testArrayAccess($word)
    {
        $rk = new ReservedKeywords();
        $this->assertTrue(isset($rk[$word]));
    }

    /**
     * @dataProvider getDataKeywords
     */
    public function testStaticCheck($word)
    {
        $this->assertTrue(ReservedKeywords::isReservedKeyword($word));
    }

    /**
     * @dataProvider getDataKeywords
     */
    public function testOffsetGet($word)
    {
        $rk = new ReservedKeywords();
        $this->assertNotNull($rk[$word]);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testOffsetSet()
    {
        $rk = new ReservedKeywords();
        $rk['_TEsT_'] = 'test';
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testOffsetUnset()
    {
        $rk = new ReservedKeywords();
        unset($rk['__CLASS__']);
    }
}