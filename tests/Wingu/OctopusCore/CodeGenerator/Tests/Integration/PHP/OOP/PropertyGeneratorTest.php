<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Integration\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\VarTag;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\Modifiers;
use Wingu\OctopusCore\Reflection\ReflectionProperty;

class PropertyGeneratorTest extends TestCase {

    public function testCompleteClassPropertyGenerator() {
        $ccg = new PropertyGenerator('array', array(1, null, 'string'));
        $ccg->setStatic(true);
        $ccg->setVisibility(Modifiers::VISIBILITY_PROTECTED);

        $classDoc = new DocCommentGenerator('Short property description.', "Long description.\nOn multiple lines.", [new VarTag('array')]);
        $ccg->setDocumentation($classDoc);

        $this->assertSame(file_get_contents(dirname(__DIR__).'/../Expected/CompleteClassPropertyGenerator.txt'), $ccg->generate());
    }

    public function testFromReflection() {
        $reflection1 = new ReflectionProperty('Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\ClassProperties', 'array');
        $ccg1 = PropertyGenerator::fromReflection($reflection1);
        $this->assertSame(file_get_contents(dirname(__DIR__).'/../Expected/CompleteClassPropertyGenerator.txt'), $ccg1->generate());

        $reflection2 = new ReflectionProperty('Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\ClassProperties', 'property');
        $ccg2 = PropertyGenerator::fromReflection($reflection2);
        $this->assertSame('public $property;', $ccg2->generate());

        $reflection3 = new ReflectionProperty('Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\ClassProperties', 'protectedProperty');
        $ccg3 = PropertyGenerator::fromReflection($reflection3);
        $this->assertSame('private $protectedProperty = 1;', $ccg3->generate());
    }
}