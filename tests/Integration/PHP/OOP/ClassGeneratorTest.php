<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Integration\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\Modifiers;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator;
use Wingu\OctopusCore\CodeGenerator\CodeLineGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\UseTraitGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassConstantGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\BaseTag;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\ParamTag;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\ReturnTag;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\ThrowsTag;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\VarTag;
use Wingu\OctopusCore\Reflection\ReflectionClass;

class ClassGeneratorTest extends TestCase {

    public function testClassGeneratorWithConstantsOnly() {
        $cg = new ClassGenerator('MyClass', 'Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures');

        $constants = array();
        $constants[] = new ClassConstantGenerator('MY_CONST1', null);
        $constants[] = new ClassConstantGenerator('MY_CONST2', "it's a string");
        $cg->setConstants($constants);

        $this->assertSame(file_get_contents(dirname(__DIR__).'/../Expected/ClassGeneratorWithConstantsOnly.txt'), $cg->generate());
    }

    public function testCompleteClassGenerator() {
        $cg = new ClassGenerator('MyClass', 'Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures');
        $cg->setFinal(true);
        $cg->setExtends('c1');
        $cg->setImplements(['ci1', 'ci2', '\Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\Dummy\ci3', '\Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\Dummy\ci4']);

        $classDocAnnotation = new BaseTag('baseAnnotation', 'testing');
        $classDoc = new DocCommentGenerator('Short class description.', "Long description.\nOn multiple lines.", [$classDocAnnotation]);
        $cg->setDocumentation($classDoc);

        $uses = array();
        $uses[] = new UseTraitGenerator('ct1');
        $uses[] = new UseTraitGenerator('ct2', ['ct2::myfunc as protected myfunc2;', 'ct2::func as func2;']);
        $cg->setTraitUses($uses);

        $constants = array();
        $constants[] = new ClassConstantGenerator('MY_CONST1', null);
        $constant2Doc = new DocCommentGenerator('My second constant.');
        $constant2Doc->addAnnotationTag(new VarTag('string'));
        $constants[] = new ClassConstantGenerator('MY_CONST2', "it's a string", $constant2Doc);
        $cg->setConstants($constants);

        $properties = array();
        $properties[] = new PropertyGenerator('publicProperty');
        $properties[] = new PropertyGenerator('protectedProperty', 1, Modifiers::MODIFIER_PROTECTED);
        $properties[] = new PropertyGenerator('privateProperty', 'private', Modifiers::MODIFIER_PRIVATE);
        $propertyStatic = new PropertyGenerator('array', array(1,null,'string'), [Modifiers::MODIFIER_PROTECTED | Modifiers::MODIFIER_STATIC]);
        $propertyStaticDoc = new DocCommentGenerator('Static property.', null, [new VarTag('array')]);
        $propertyStatic->setDocumentation($propertyStaticDoc);
        $properties[] = $propertyStatic;
        $cg->setProperties($properties);

        $methods = array();
        $method1 = new MethodGenerator('__construct');
        $method1Doc = new DocCommentGenerator('Constructor.');
        $method1Doc->addAnnotationTag(new ParamTag('mixed', 'param1', 'Parameter 1.'));
        $method1Doc->addAnnotationTag(new ParamTag('string', 'param2', 'Parameter 2.'));
        $method1->setDocumentation($method1Doc);
        $method1->addParameter(new ParameterGenerator('param1'));
        $method1Param2 = new ParameterGenerator('param2');
        $method1Param2->setDefaultValue(null);
        $method1->addParameter($method1Param2);
        $methods[] = $method1;

        $method2 = new MethodGenerator('protectedFunc', 'return count($array);');
        $method2->setVisibility(Modifiers::VISIBILITY_PROTECTED);
        $method2->addParameter(new ParameterGenerator('array', array()));
        $method2Doc = new DocCommentGenerator('Protected function.');
        $method2Doc->addAnnotationTag(new ParamTag('array', 'array', 'The array to count.'));
        $method2Doc->addAnnotationTag(new ReturnTag('integer'));
        $method2->setDocumentation($method2Doc);
        $methods[] = $method2;

        $method3 = new MethodGenerator('privateStaticFunction');
        $method3->setStatic(true);
        $method3->setVisibility(Modifiers::VISIBILITY_PRIVATE);
        $method3->setFinal(true);
        $method3Doc = new DocCommentGenerator('Private static function.', 'This is my long function.');
        $method3Doc->addAnnotationTag(new ParamTag('\DateTime', 'datetime', 'The date time.'));
        $method3Doc->addAnnotationTag(new ReturnTag('boolean'));
        $method3Doc->addAnnotationTag(new ThrowsTag('\InvalidArgumentException', 'If the date is in the past.'));
        $method3->setDocumentation($method3Doc);
        $method3->addParameter(new ParameterGenerator('datetime', null, '\DateTime'));
        $method3->addBodyLine(new CodeLineGenerator('if ($datetime < new \DateTime(\'now\')) {'));
        $method3->addBodyLine(new CodeLineGenerator('throw new \InvalidArgumentException(\'Date is in the past.\');', 1));
        $method3->addBodyLine(new CodeLineGenerator('}'));
        $method3->addBodyLine(new CodeLineGenerator());
        $method3->addBodyLine(new CodeLineGenerator('return true;'));
        $methods[] = $method3;

        $cg->setMethods($methods);

        $this->assertSame(file_get_contents(dirname(__DIR__).'/../Expected/CompleteClassGenerator.txt'), $cg->generate());
    }

    public function testFromReflection() {
        $reflection = new ReflectionClass('Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\MyClass');
        $cg = ClassGenerator::fromReflection($reflection);

        $this->assertSame(file_get_contents(dirname(__DIR__).'/../Expected/CompleteClassGenerator.txt'), $cg->generate());
    }

    public function testFromReflectionExtendsNotSamesNamespace() {
        $ns = 'Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures';
    	$reflection = new ReflectionClass($ns.'\ClassGeneratorReflectionExtendsNotSameNamespace');
        $cg = ClassGenerator::fromReflection($reflection);

        $this->assertSame('\\'.$ns.'\Test\MyExtension', $cg->getExtends());
    }
}