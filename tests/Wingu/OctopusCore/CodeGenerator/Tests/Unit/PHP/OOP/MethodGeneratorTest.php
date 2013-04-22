<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\Modifiers;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator;

class MethodGeneratorTest extends TestCase {

    public function getDataGenerateSignature() {
        return array(
            ['func1', false, false, Modifiers::VISIBILITY_PUBLIC, false, 'public function func1()'],
            ['func2', false, false, Modifiers::VISIBILITY_PRIVATE, true, 'private static function func2()'],
            ['func3', true, false, Modifiers::VISIBILITY_PROTECTED, true, 'final protected static function func3()'],
            ['func4', false, true, Modifiers::VISIBILITY_PROTECTED, false, 'abstract protected function func4()'],
        );
    }

    /**
     * @dataProvider getDataGenerateSignature
     */
    public function testGenerateSignature($name, $final, $abstract, $visibility, $static, $expected) {
        $mg = new MethodGenerator($name);
        $mg->setFinal($final);
        $mg->setAbstract($abstract);
        $mg->setVisibility($visibility);
        $mg->setStatic($static);

        $this->assertSame($expected, $mg->generateSignature());
    }

    /**
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\RuntimeException
     */
    public function testAbstractAndFinalAtSameTime() {
        $mg = new MethodGenerator('test');
        $mg->setFinal(true);
        $mg->setAbstract(true);
        $mg->generateSignature();
    }

    /**
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\RuntimeException
     */
    public function testAbstractAndPrivateAtSameTime() {
    	$mg = new MethodGenerator('test');
    	$mg->setAbstract(true);
    	$mg->setVisibility(Modifiers::VISIBILITY_PRIVATE);
    	$mg->generateSignature();
    }
}