<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\UseTraitGenerator;

class UseTraitGeneratorTest extends TestCase {

    public function getDataGenerate() {
        return array(
            ['MyTrait', [], 'use MyTrait;'],
            ['MyTrait2', ['func1 as protected;', 'func2 as func;'], "use MyTrait2 {\n    func1 as protected;\n    func2 as func;\n}"]
        );
    }

    /**
     * @dataProvider getDataGenerate
     */
    public function testGenerate($use, $conflictsResolutions, $expected) {
        $ug = new UseTraitGenerator($use, $conflictsResolutions);
        $this->assertSame($expected, $ug->generate());
    }

    /**
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testSetUseFail1() {
        $mock = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\UseTraitGenerator', null, [], '', false);
        $mock->setTraitClass('wrong name');
    }
}