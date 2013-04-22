<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Integration\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassConstantGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\VarTag;
use Wingu\OctopusCore\Reflection\ReflectionConstant;

class ClassConstantGeneratorTest extends TestCase {

    public function testCompleteClassConstantGenerator() {
        $ccg = new ClassConstantGenerator('MY_CONST', "it's a string");

        $constantDoc = new DocCommentGenerator('Short constant description.', "Long description.\nOn multiple lines.", [new VarTag('string')]);
        $ccg->setDocumentation($constantDoc);

        $this->assertSame(file_get_contents(dirname(__DIR__).'/../Expected/CompleteClassConstantGenerator.txt'), $ccg->generate());
    }

    public function testFromReflection() {
        $reflection = new ReflectionConstant('Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\ClassConstants', 'MY_CONST');
        $ccg = ClassConstantGenerator::fromReflection($reflection);
        $this->assertSame(file_get_contents(dirname(__DIR__).'/../Expected/CompleteClassConstantGenerator.txt'), $ccg->generate());

        $reflection = new ReflectionConstant('Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\ClassConstants', 'SIMPLE_CONST');
        $ccg = ClassConstantGenerator::fromReflection($reflection);
        $this->assertSame('const SIMPLE_CONST = null;', $ccg->generate());
    }
}